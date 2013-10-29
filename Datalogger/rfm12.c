// rfm12.c
//
// Use of bcm2835 library
// Author: Mike McCauley
// Copyright (C) 2012 Mike McCauley
// $Id: RF22.h,v 1.30 2012/05/30 01:51:25 mikem Exp $
//
// Interface to RFM12 connecting to raspberry spi interface
//
// After installing bcm2835, you can build and start this 
// with something like:
// gcc -o rfm12 rfm12.c -l bcm2835
// sudo ./rfm12
//
// Or you can test it before installing with:
// gcc -o rfm12 -I ../../src ../../src/bcm2835.c rfm12.c
// sudo ./rfm12
//


#include <bcm2835.h>
#include <stdio.h>
#include <fcntl.h>

#define STX 0x02
#define ETX 0x03
#define true 1
#define false 0
#define uchar unsigned char

// Input on RPi pin GPIO 15 (nIRQ)
#define PIN RPI_GPIO_P1_15

/** radio telegram content and assigned variables */
char recbuf[10];
uint8_t bufferposition;
uint8_t Checksum;
uint8_t rec_started;

/** From radio modul received data */
uint8_t DataReceived;

/** values sensor 2 */
int8_t Sensor2Value=0;
int8_t Sensor2Correction=-50;

/** values sensor 3 */
int8_t Sensor3Value=0;
int8_t Sensor3Correction=-50;

uint8_t rec_started;
uint8_t fd;
char *filename2 = "/home/pi/sensorvalue2";
char *filename3 = "/home/pi/sensorvalue3";
mode_t mode = S_IRUSR | S_IWUSR | S_IRGRP | S_IROTH;
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
    //bcm2835_delayMicroseconds(50);
    return reply;
}

/**
   * 868MHz-band, 868 MHz, 4.8kbps, receiver activated,\n
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
    
    //rf12_xfer(0x8239); // start transmitter cw mode --> Test RFM12
}

void Enable_receiver(void)
{
    rf12_xfer(0x82c8);
    rf12_xfer(0xca83);
}

void RestartFifoFill_receiver(void)
{
  rf12_xfer(0xca81); // stop FIFO fill
  bcm2835_delay(1);
  rf12_xfer(0xca83); // restart FIFO synchron pattern recognition
}

/** Receive telegram from radio modul*/
void RadioReceive(void)
{
   DataReceived = (uchar) rf12_xfer(0xb000);               // receiver FIFO read command

	if ((DataReceived == ETX)&rec_started)			// ETX -> end of telegram
    {
		bufferposition--;							// array last position (contents checksum)
		Checksum -= recbuf[bufferposition];			// correct checksum (the last value in array is the checksum)
		if (Checksum == recbuf[bufferposition]) 	// Checksum OK -> display new value
		{
		   recbuf[bufferposition] = '\0';			// set end of string, delete checksum
			if (recbuf[0]=='A')					    // Sensor A
			{
			        Sensor2Value = atoi(&recbuf[1]);		 // convert in integer
			        if (Sensor2Value != 0)
                    {
                        Sensor2Value += Sensor2Correction;  // correct value if conversion ok
                    }

			  //printf("Sensorvalue 2: %d \n", Sensor2Value);

              fd = open(filename2, O_WRONLY | O_CREAT |  O_NOCTTY, mode);	// open file for data to php
			  write(fd, &recbuf[1], 3);					                    // write value in file
              close (fd);										            // close file
              
             }
			if (recbuf[0]=='B')					                    // Sensor B
			{
			        Sensor3Value = atoi(&recbuf[1]);		 		// convert in integer
			        if (Sensor3Value != 0)
                    {
                        Sensor3Value += Sensor3Correction; 	// correct value if conversion ok
                    }

              //printf("Sensorvalue 3: %d \n", Sensor3Value);

              fd = open(filename3, O_WRONLY | O_CREAT);	// open file for data to php
			  write(fd, &recbuf[1], 3);					// write value in file
              close (fd);                               // close file
            }
		}
        RestartFifoFill_receiver();

		rec_started = 0;
		bufferposition = 0;
    }
    else
    {
		if (DataReceived == STX) 				// STX -> start of telegram -> save following characters
		 {
			rec_started = 1;
			Checksum = 0;
		 }
		else if ((rec_started) && (bufferposition < 6)) 	// save telegram content, n -> next array position
		{
		recbuf[bufferposition++] = DataReceived;
		Checksum += DataReceived;
		}
		else if (bufferposition>=5)					// over 5 characters received -> reject telegram
		{
        RestartFifoFill_receiver();
		rec_started = 0;
		bufferposition = 0;
 	    }
	}
}


int main(int argc, char **argv)
{
    // If you call this, it will not actually access the GPIO
   // Use for testing
  //        bcm2835_set_debug(1);
    uint16_t result;
    
    if (!bcm2835_init())
	return 1;
    
    bcm2835_spi_begin();
    bcm2835_spi_setBitOrder(BCM2835_SPI_BIT_ORDER_MSBFIRST);      // The default
    bcm2835_spi_setDataMode(BCM2835_SPI_MODE0);                   // The default
    bcm2835_spi_setClockDivider(BCM2835_SPI_CLOCK_DIVIDER_2048);  // SPI Clock 125 kHz
    bcm2835_spi_chipSelect(BCM2835_SPI_CS1);                      // The default
    bcm2835_spi_setChipSelectPolarity(BCM2835_SPI_CS1, LOW);      // the default
    
     // Set RPI pin P1-15 to be an input (GPIO22)
    bcm2835_gpio_fsel(PIN, BCM2835_GPIO_FSEL_INPT);
    //  with a pullup
    bcm2835_gpio_set_pud(PIN, BCM2835_GPIO_PUD_UP);
    
    rfm12b_initialize();
    //result = rf12_xfer(0x0000); // request status (dummy)
    //printf("Read from SPI: %04X \n", result);
    
    Enable_receiver( );
    
    //result = rf12_xfer(0x0000); // request status (dummy)
    //printf("Read from SPI: %04X \n", result);
    
    while (1)
    {
     if (bcm2835_gpio_lev(PIN)==false) //RFM12 nIRQ received, polling pin
        {
         RadioReceive(); // receiver FIFO read command
         
        }
    
    bcm2835_delay(1);
    };
    
    
    bcm2835_spi_end();
    bcm2835_close();
    return 0;
}

