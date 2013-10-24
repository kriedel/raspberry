/** @file rfm12b.c
*
* @author Peter Emmerling
*
* @date 31.01.2013
*
* @version 0.7
* basic functions like init_device_list, rfm12b_initialize, calc_crc, char_to_int, int_to_char,
* rf12_xfer, rf12_txdata, rf12_rxdata, receive_data, transmit_data, spi_init, waitus, wait_rfm12,
* activate_receiver, deactivate_receiver.
*
*/

#include "rfm12b.h"
#include <stdio.h>
#include <stdlib.h>
#include <bcm2835.h>
#include <unistd.h>
#include <string.h>
#include <stdint.h>
#include <sys/time.h>


#define GERAETID '1' //ID des Gerätes (eindeutig)
#define GERAETETYP Master //definiert ob Master oder Slave

/*!
 * \var bool Geraeteliste[MaxTeilnehmer]
 * @brief device list
 * 
 * not used right now
*/ 
bool Geraeteliste[MaxTeilnehmer];
/*!
 * \var bool GeraetelisteEmpfaenger[MaxTeilnehmer]
 * @brief device list receiver
 * 
 * not used right now
*/ 
bool GeraetelisteEmpfaenger[MaxTeilnehmer];
/*!
 * \var unsigned int debugvalue
 * @brief content of status register (0x0000)
 * 
 * can be used to get current status of rfm12b
*/ 
unsigned int debugvalue; // Wert kann über das Register 0x0000 des RFM12 gelesen werden
/*!
 * \var unsigned char res_buffer[16]
 * @brief global data buffer
 * 
 * contains received data bytes
*/
unsigned char res_buffer[16];


/**
   * \param Timeout value, timeout is reached
   * \return value of time variable ended up as nIRQ got low or zero if timeout reached (nIRQ always high)
   *
   * Waiting for nIRQ getting low, needed for transmission control\n
   * nIRQ is getting low if rfm12b is ready for next byte to transmit
   */
unsigned int wait_rfm12( unsigned int Timeout)
{
    unsigned int T;
    T = 0;
    //Nsel = 0;
    do
        {
            T += 1;
            waitus (100);
        }
    //solang keine Daten und T < Timeoutwert
    //while (( (unsigned int) bcm2835_gpio_lev(RPI_V2_GPIO_P1_21) == 0) && (T < Timeout));  //0 bei Timeout senden
    while (((unsigned int) bcm2835_gpio_lev(RPI_GPIO_P1_15) == 1)&& (T < Timeout));//RPI_V2_GPIO_P1_15 entspricht GPIO22!!!
    //printf("nIRQ: %d, Durchlauf %d \n", (unsigned int)bcm2835_gpio_lev(RPI_V2_GPIO_P1_15), ct);//RPI_V2_GPIO_P1_15 entspricht GPIO22!!!
    //printf("\t T: %d \n",T);
    waitus(200);
    if (T < Timeout) return T;
    else return 0;
}

/**
   * create delay in microseconds
   */
void waitus (unsigned int delaytime)
{
    usleep(delaytime);

}

/**
   * enables FIFO fill of rfm12b after synchron pattern reception,\n
   * receiver activated either since initialization or previous transmission event
   */
void activate_receiver( void)
{
    //rf12_xfer(0xca81); //B2.8 Fifo8 , Sync , !ff , Dr
    rf12_xfer(0xca83); //B2.8 Fifo8 , Sync , ff , Dr
    //rf12_xfer(0x82c9); //B2.3 er, ebb, !et, !es, ex, !eb, !ew, dc
    rf12_xfer(0);
}

/**
   * disables FIFO fill of rfm12b, receiver keeps active
   */
void Deactivate_receiver( void)
{
    //rf12_xfer(0x8209); //B2.3 !er , !ebb , !Et , !Es , Ex , !eb , !ew , dc
    rf12_xfer(0xca81); //B2.8 Fifo8 , Sync , !ff , Dr
}

/**
   * Initialization of RPi's SPI-Bus, bcm2835-library used,\n
   * GPIO 7 (Pin 26) used for Chip Enable and GPIO-Pin 22 (Pin 15!) for nIRQ
   */
void spi_init()
{
    if (!bcm2835_init())
        exit (1);
    bcm2835_spi_begin();
    bcm2835_spi_setBitOrder(BCM2835_SPI_BIT_ORDER_MSBFIRST);
    bcm2835_spi_setDataMode(BCM2835_SPI_MODE0);
    bcm2835_spi_setClockDivider(BCM2835_SPI_CLOCK_DIVIDER_4096);   // 2MHz
    bcm2835_spi_chipSelect(RFM_CE);
    bcm2835_spi_setChipSelectPolarity(RFM_CE, LOW);

    //Set IRQ pin details
    bcm2835_gpio_fsel(RFM_IRQ, BCM2835_GPIO_FSEL_INPT);
    bcm2835_gpio_set_pud(RFM_IRQ, BCM2835_GPIO_PUD_UP);
}

/**
   * low level function sending spi-command (a word) to rfm12b,\n
   * used almost from all other functions
   */
uint16_t rf12_xfer(uint16_t cmd)
{
    char buffer[2];
    uint16_t reply;
    buffer[0] = cmd >> 8;
    buffer[1] = cmd;
    bcm2835_spi_transfern(buffer,2);
    reply = buffer[0] << 8;
    reply |= buffer[1];
    return reply;
}

/**
   * 433MHz-band, 434.15 MHz, 4.8kbps, receiver activated,\n
   * Synchronization activation, 90kHz output frequency, 134 kHz receiver bandwidth
   */
void rfm12b_initialize (void)
{
    rf12_xfer(0x80e7); //B2.2 el , ef , 868band, 11.5pf
    rf12_xfer(0x82d9); //B2.3 er , ebb , !Et , Es , Ex , !eb , !ew , dc
    rf12_xfer(0xa67c); //B2.4 868,3 MHz
    rf12_xfer(0xc647); //B2.5 4.8kbps
    rf12_xfer(0x94a4); //B2.6 Vdi Fast , 134 kHz Bandbreite, 0db Abschwächung , -79dbm Schwellwert
    rf12_xfer(0xc2ac); //B2.7 Al , !ml , Dig , Dqd4
    rf12_xfer(0xca81); //B2.8 Fifo8 , Sync , !ff , Dr
    rf12_xfer(0xc483); //B2.10 Keep Offset , No Rstric , !st , !fi , Oe , En
    rf12_xfer(0x9850); //B2.11 90 kHz , power - 0 dB
    rf12_xfer(0xe000); //B2.13 no wakeup
    rf12_xfer(0xc800); //B2.14 !en
    rf12_xfer(0xc000); // 1 MHz , 2.2V
    rf12_xfer(0x0000); // request status (dummy)
}

/**
   * int to char
   */
char int_to_char(int integer)
{
    //Achtung, nur bis 9!
    if ((integer>=0) && (integer<=9))
        return (char)integer+48;
    else
        return 'X';
}

/**
   * char to int
   */
int char_to_int(char inchar)
{
    //Achtung, nur bis 9!
    if ((inchar>='0') && (inchar<='9'))
        return (int)inchar-48;
    else
        return 0;
}

/**
   * device list, to do
   */
void init_device_list (void)
{
    short i;
    for (i=0; i<10; i++)
        {
            Geraeteliste[i] = 0;    //initialisiere Liste
        }
    for (i=0; i<10; i++)
        {
            GeraetelisteEmpfaenger[i] = 0;    //initialisiere Liste
        }
    Geraeteliste[char_to_int(GERAETID)] = 1;  //füge in Liste die eigene ID als vorhanden ein
}

//Rohdaten empfangen

/**
   * \param number number data bytes to receive
   * \param timeout if true, polling nIRQ for some time to see,\n
   * if there is data to receive within time --> used for acknowledge\n
   * if false, waiting for data forever (waiting while nIRQ is high)
   * \return 0 if data received (one byte reception enough for successful acknowledge), 1 if timeout remains true
   * 
   * receiving data bytes
   */
unsigned int rf12_rxdata(unsigned char* data, unsigned char number, bool timeout)
{
    unsigned char i;
    unsigned int T;
    struct timeval tv1,tv2;
    //Start
    //printf("vor aktivieren \n");
    activate_receiver();
    //printf("bin drin \n");
    for (i=0; i<number; i++)
        {
            //if (wait_rfm12(TIMEOUT) == 0) return 0;
            rf12_xfer(0x0000);
            //rf12_xfer(0xB000);
            if (timeout==true)
                {
                    //realize timout for IRQ and exit for-loop
                    //and deactivate receiver before returning 1
                    gettimeofday(&tv1, NULL);
                    T=0;
                    //printf("intlevel: %d \n",bcm2835_gpio_lev(RFM_IRQ));
                    do
                        {
                            T++;
                        }
                    while (bcm2835_gpio_lev(RFM_IRQ) && (T<TIMEOUT_ACK));
                    if (T<TIMEOUT_ACK)
                        {
                            rf12_xfer(0);
                            *data=rf12_xfer(0xB000);
                            data++;
                            timeout=false;
                        }
                    gettimeofday(&tv2, NULL);

                    if (timeout == true)
                        {
                            //printf("\033[35mno data received:  \n \033[37m");
                            //waitus(1000);
                            Deactivate_receiver();
                            printf ("\033[33m\t\tto true von %ld.%06ld \n\033[37m", tv1.tv_sec , tv1.tv_usec);
                            printf ("\033[33m\t\tto true bis %ld.%06ld \n\033[37m", tv2.tv_sec , tv2.tv_usec);
                            return 1;
                        }
                    else
                        {
                            //printf("\033[35mdata received:  \n\033[37m");
                            //printf("data_received0: %d \n",data_received);
                            Deactivate_receiver();
                            printf ("\033[33m\t\tto false von %ld.%06ld \n\033[37m", tv1.tv_sec , tv1.tv_usec);
                            printf ("\033[33m\t\tto false bis %ld.%06ld \n\033[37m", tv2.tv_sec , tv2.tv_usec);
                            return 0;
                        }
                }
            else
                {
                    // printf("before nIRQ \n");
                    while(bcm2835_gpio_lev(RFM_IRQ));
                    rf12_xfer(0);
                    *data=rf12_xfer(0xB000);
                    data++;
                }

        }
//Ende
//waitus(1000);
    Deactivate_receiver();

    return 0;
}
//Rohdaten aufbereiten

/**
   * \param timeout forwarded to rf12_rxdata\n
   * \param n number of bytes to be received\n
   * \return address to first received byte if successful, NULL if not
   * 
   * if timeout is true, reception of only one byte containing the back transmitted\n
   * destination id from receiver is enough for verifying sucessful data transmission\n
   * getting data through rf12_rxdata,\n
   * doing crc-check, copying data to global variable res_buffer
   */
unsigned char* receive_data(bool timeout, unsigned int n)
{
    unsigned char data_len = n-1;  //Länge des Datenpaketes
    unsigned int crcmask = 0x99;  //CRC Maske 1001 1001
    short crc = 0;                //CRC Initialisierungswert
    unsigned int i;               //Initialisierungswert
    unsigned char* resdata;
    resdata = (unsigned char*) malloc(sizeof(unsigned char)*n);


    //Daten empfangen
    if (!rf12_rxdata (resdata,n,timeout))
        {
            //Über die Länge der Daten
            for(i = 0; i < data_len; i++)
                {
                    //CRC bilden
                    crc = calc_crc(crc, *(resdata+i), crcmask);
                }
            if ((crc==*(resdata+data_len)) || (timeout==true)) //if timeout-mode --> no matter what data received --> error on receiver-device --> transmitting package repeatingly must be initiated
                {
                    memcpy(res_buffer, resdata, sizeof(unsigned char)*n);
                    free(resdata);
                    return &res_buffer[0];
                }
            else
                {
                    rf12_xfer(0x0000);
                    free(resdata);
                    return NULL;
                }
        }
    else
        {
            free(resdata);
            return NULL;
        }


}

/**
   * \param tn device id of destination device\n
   * \param toSend data structure to be transmitted\n
   * \return nothing to return
   * 
   * 13 data bytes (already in param toSend) will be transmitted,\n
   * source id (data byte 14), destination id (data byte 15)\n 
   * and crc (16) are added, data ist send via function rf12_txdata,\n
   * fix data length of 16 bytes will be transmitted
   */
void transmit_data(unsigned short tn, datenpaket* toSend)
{
    unsigned int data_len= 15; //Länge des Datenstroms
    unsigned int crcmask = 0x99; //Polynom für CRC Polynom 1001 1001
//    unsigned char daten[]= dataToSend; //Array für Daten
    unsigned char* daten = (unsigned char *) malloc(sizeof(char)*16);
    //strcpy(daten,toSend->data);
    strncpy((char *)daten,toSend->data,13); //nicht null-terminiert ;-)

    short crc = 0; //Initialisierung von ’crc’
    unsigned int i;

    daten[13] = GERAETID;
    daten[14] = int_to_char(tn);
    toSend->qid = daten[13];
    toSend->zid = daten[14];

    for(i = 0; i < data_len; i++)
        //Über die Länge der Daten
        {
            crc = calc_crc(crc, daten[i], crcmask); //CRC bilden
        }
    daten[15] = (unsigned char) crc;
    toSend->crc = daten[15];
    rf12_txdata (daten, 16);
    //rf12_xfer(0x0000);
    free(daten);
    return;
}

/**
   * \param data data structure to be transmitted\n
   * \param number number of bytes to be transmitted (16)\n
   * \return 1 if success, 0 if transmission failed
   * 
   * activating transmitter of rfm12b, sending preamble 3 times\n
   * sending sync word, sending data bytes, sending preamble 3 times\n 
   * deactivate transmitter, reactivate receiver\n
   */
unsigned int rf12_txdata(unsigned char *data, unsigned char number)
{
    unsigned int i=0;
    //unsigned char *tmp;
    //tmp=data;

    rf12_xfer(0x8238); // !er,!ebb,et,es,  ex,!eb,!ew,!dc
    if (wait_rfm12(TIMEOUT) == 0) return 0;
    rf12_xfer(0x0000); //read status to get nIRQ high
    if (wait_rfm12(TIMEOUT) == 0) return 0;

    rf12_xfer(0xB8AA); // Preamble AA
    if (wait_rfm12(TIMEOUT) == 0) return 0;
    rf12_xfer(0xB8AA); // Preamble AA
    if (wait_rfm12(TIMEOUT) == 0) return 0;
    rf12_xfer(0xB8AA); // Preamble AA
    if (wait_rfm12(TIMEOUT) == 0) return 0;
    rf12_xfer(0xB82D); // SyncWord Teil 1
    if (wait_rfm12(TIMEOUT) == 0) return 0;
    rf12_xfer(0xB8D4); // SyncWord Teil 2
    for (i=0; i<number; i++)
        {
            if (wait_rfm12(TIMEOUT) == 0) return 0;
            rf12_xfer(0xB800|*(data++));
            debugvalue = rf12_xfer (0x0000);
        }

    if (wait_rfm12(TIMEOUT) == 0) return 0;
    rf12_xfer(0xB8AA); // Preamble AA
    if (wait_rfm12(TIMEOUT) == 0) return 0;
    rf12_xfer(0xB8AA); // Preamble AA
    if (wait_rfm12(TIMEOUT) == 0) return 0;
    rf12_xfer(0xB8AA); // Preamble AA
    if (wait_rfm12(TIMEOUT) == 0) return 0;
    //rf12_xfer(0x8208); // !er,!ebb,!et,!es,  ex,!eb,!ew,!dc
    //disable transmitter, reactivate receiver:
    rf12_xfer(0x82d9); //B2.3 er , ebb , !Et , Es , Ex , !eb , !ew , dc
    printf("\t\t\tTransmission ended \n");

    return 1;
}

/**
   * \param crc crc recalculated\n
   * \param daten current data byte to be added to crc\n
   * \param mask crc mask\n
   * \return recalculated crc\n
   */
unsigned int calc_crc(unsigned int crc, unsigned int daten, unsigned int mask)
{
    unsigned char i;
    //für alle Nutzdaten des Datenpaketes
    for(i = 0; i < 15; i++)
        {
            //wenn crc Initialisierungswert XOR Daten und 1 !=0
            if((crc ^ daten) & 1)
                {
                    //CRC berechnen (rechtsschieben XOR CRC-Maske)
                    crc = (unsigned int)((crc >> 1) ^ mask);
                }
            //wenn crc Initialisierungswert XOR Daten und 1 ==0
            else
                {
                    //CRC berechnen (rechtsschieben)
                    crc >>= 1;
                }
            //nächster Buchstabe
            daten >>= 1;
        }
    //CRC Wert für Nutzdaten
    return (crc);
}
