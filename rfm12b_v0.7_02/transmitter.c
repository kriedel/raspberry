 
 /** @file transmitter.c
 *   
 *  @author Peter Emmerling 
 * 
 *  @date 31.01.2013 
 * 
 *  @version 0.7 
 *  basic transmitter functionality, handshake included
 */

#include <stdio.h>
#include <stdlib.h>
#include <bcm2835.h> 
#include <unistd.h> 
#include <string.h> 
#include <sys/time.h> 
#include "rfm12b.h"


int main(int argc, char *argv[] )
{
    int i;
    //geraetetyp Geraetetyp;
    unsigned char* ptr_string;
    datenpaket *mydatenpaket;
    struct timeval tv1,tv2;
    
    char * strarray[] = { "ABCDEFGHIJKLM", "NOPQRSTUVWXYZ", "abcdefghijklm", 
                                "nopqrstuvwxyz", "1234567891234", "ABCDEFGHIJKLM", 
                                "NOPQRSTUVWXYZ", "abcdefghijklm", "nopqrstuvwxyz", "1234567891234" };
    
    spi_init();
    printf("\t nIRQ  : %d \n",(unsigned int) bcm2835_gpio_lev(RPI_GPIO_P1_15));//RPI_V2_GPIO_P1_15 entspricht GPIO22!!!
    rfm12b_initialize();
    printf("\t nIRQ 2: %d \n",(unsigned int) bcm2835_gpio_lev(RPI_GPIO_P1_15));//RPI_V2_GPIO_P1_15 entspricht GPIO22!!!
    init_device_list ();    
    
    //Geraetetyp = GERAETETYP;
    //(Geraetetyp == Master)?(GeraeteLED=1):(GeraeteLED=0);    
    printf("\t nIRQ 3: %d \n",(unsigned int) bcm2835_gpio_lev(RPI_GPIO_P1_15));//RPI_V2_GPIO_P1_15 entspricht GPIO22!!!
    
    mydatenpaket = (datenpaket *) malloc(sizeof(datenpaket));        
    waitus(2000000);

    for (i=0; i<10; i++)
    {        
                
        strcpy(mydatenpaket->data, strarray[i]);
        printf("paket tosend: %s \n",mydatenpaket->data);
        printf("\033[36mlevel IRQ: %d \033[37m\n",bcm2835_gpio_lev(RFM_IRQ));
        transmit_data(2,mydatenpaket);
        gettimeofday(&tv1, NULL);
        printf("paket sent: %s \n",mydatenpaket->data);
        printf("qid sent: %c \n",mydatenpaket->qid);
        printf("zid sent: %c \n",mydatenpaket->zid);
        printf("crc sent: 0x%x \n",mydatenpaket->crc);
        printf("Waiting for acknowledge-paket\n");
        
        //rf12_xfer(0x82d9);
        printf("\033[36mlevel IRQ: %d \033[37m\n",bcm2835_gpio_lev(RFM_IRQ));
        //rf12_xfer(0x0000);
        //printf("\033[36mlevel IRQ: %d \033[37m\n",bcm2835_gpio_lev(RFM_IRQ));
        //waitus(1000000);
        gettimeofday(&tv2, NULL);
        printf ("von %ld.%06ld \n", tv1.tv_sec , tv1.tv_usec);
        printf ("bis %ld.%06ld \n", tv2.tv_sec , tv2.tv_usec);

        ptr_string = receive_data(true,1);
       
        if (ptr_string == NULL)
        {
            waitus(1500000);
            
            while ((ptr_string == NULL))
            {
                printf("\033[31m\tTRANSMISSION is repeated ;-),no ack received \n\033[37m");
                transmit_data(2,mydatenpaket);
                //waitus(200);
                ptr_string = receive_data(true,1);
                printf("\033[31m\tmydatenpaket->zid: %c \n",mydatenpaket->zid);
                if (ptr_string != NULL)
                {
                    if (*ptr_string != mydatenpaket->zid)
                        ptr_string = NULL;
                }
                waitus(1500000);
            }
            printf("\033[32mTRANSMISSION successful after repetition ;-),first character: %c \n\033[37m", *ptr_string);
        } else
        {
            
            printf("\033[32mTRANSMISSION successful ;-),first character: %c \n\033[37m", *ptr_string);
        }
        //ptr_string = empfange_daten();
        //printf("resbuffer[%d]: ",i);
        //for (j=0; j<16; j++) {
            //printf("%c",(char)(*(ptr_string+j)));
        //}   
        //printf("\n");
        //fflush(0);
        waitus(1500000);
        
        
        
    }
    free(mydatenpaket);
    bcm2835_spi_end();
       
    return 0;
}
