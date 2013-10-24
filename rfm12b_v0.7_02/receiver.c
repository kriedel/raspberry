/** @mainpage rfm12b 
 *  
 * @section sec1 Introduction 
 * This project implements the basic communication between two rfm12b-moduls,
 * each connected to a RPi, B-Version (Raspberry Pi, what a wonderful low cost mini-computer ;-)).
 * 
 * The transceiver is controlled over the SPI-Bus of the RPi.
 * 
 * @section sec2 Project Purpose 
 * Provides a stable hand-shake communication between transmitter and receiver.
 * For SPI-communication the bcm2835-library is used:
 * <a href="http://www.open.com.au/mikem/bcm2835/bcm2835-1.5.tar.gz">Download</a> /
 * <a href="http://www.open.com.au/mikem/bcm2835/">Take a look</a>
 * 
 * @section sec3 Structure
 * @subsection sec3_1 Connections
 * <table border="0" bgcolor="mintcream">
 * <tr><th width="100px" align="left">RFM12b</th><th width="100px" align="center"><---></th><th width="130px" align="left">Raspberry Pi</th><th width="100px" align="left">Pin number</th></tr>
 * <tr><td>GND</td><td align="center"><---></td><td>GND</td><td>(Pin 25)</td></tr>
 * <tr><td>VDD</td><td align="center"><---></td><td>3.3V</td><td>(Pin 17)</td></tr>
 * <tr><td>FSK/DAA/nFFS</td><td align="center"><---></td><td>über 10k auf 3.3V </td><td>(Pin 01)</td></tr>
 * <tr><td>SDO</td><td align="center"><---></td><td>MISO</td><td>(Pin 21)</td></tr>
 * <tr><td>SDI</td><td align="center"><---></td><td>MOSI</td><td>(Pin 19)</td></tr>
 * <tr><td>nIRQ</td><td align="center"><---></td><td>GPIO 22</td><td>(Pin 15)</td></tr>
 * <tr><td>nSEL</td><td align="center"><---></td><td>CS1</td><td>(Pin 26)</td></tr>
 * </table>
 * 
 * 
 * 
 * <!-- 
 * RFM12b --- Raspberry Pi (version B):
 * 
 * GND              ---     GND               (Pin 25) 
 * 
 * VDD              ---     3.3V              (Pin 17)
 * 
 * FSK/DAA/nFFS     ---     über 10k auf 3.3V (Pin 1)
 * 
 * SDO              ---     MISO              (Pin 21)
 * 
 * SDI              ---     MOSI              (Pin 19)
 * 
 * nIRQ             ---     GPIO 22           (Pin 15)
 *
 * nSEL             ---     CS1               (Pin 26) 
 * -->
 * 
 * @subsection sec3_2 Protocol
 * <table bgcolor="mintcream" border="1">
 * <tr><td> 1</td><td> 2</td><td> 3</td><td> 4</td><td> 5</td><td> 6</td><td> 7</td><td> 8</td><td> 9</td><td>10</td><td>11</td><td>12</td><td>13</td><td>14</td><td>15</td><td>16</td></tr>
 * <tr><td>db</td><td>db</td><td>db</td><td>db</td><td>db</td><td>db</td><td>db</td><td>db</td><td>db</td><td>db</td><td>db</td><td>db</td><td>db</td><td>sid</td><td>did</td><td>crc</td></tr>
 * </table>
 * <b>db</b>: data byte (13); <b>sid</b>: source id; <b>did</b>: destination id; <b>crc</b>: crc byte;
 *   
 */
 
 /** @file receiver.c
 *   
 *  @author Peter Emmerling 
 * 
 *  @date 31.01.2013 
 * 
 *  @version 0.7 
 *  basic receiver functionality, handshake included
 */

 
#include <stdio.h>
#include <stdlib.h>
#include <bcm2835.h>
#include <unistd.h>
#include <string.h>
#include "rfm12b.h"


int main(int argc, char *argv[] )
{
    int i,j;
    //geraetetyp Geraetetyp;
    unsigned char* ptr_string;
    datenpaket *mydatenpaket;

    spi_init();
    rfm12b_initialize();
    init_device_list ();

    //Geraetetyp = GERAETETYP;
    //(Geraetetyp == Master)?(GeraeteLED=1):(GeraeteLED=0);
    
    mydatenpaket = (datenpaket *) malloc(sizeof(datenpaket));
    i=0;
    while (1) {
        printf("\twaiting for package \n");
        ptr_string = receive_data(false,16);
        if (ptr_string != NULL) {
            printf("\tEmpfänger: %d: ",i);
            for (j=0; j<15; j++) {
                printf("%c_",(char)(*(ptr_string+j)));
            }
            printf("\n");
            printf("crc: %x \n",*(ptr_string+j));

            //acknowledge package contains only destination id (Byte 14)
            for (j=0; j<14; j++) 
                *(mydatenpaket->data + j) = *(ptr_string+14);
            //strncpy(mydatenpaket->data,(char*)ptr_string,13);
            //*((mydatenpaket->data)+13) = '\0';
            waitus(40000);
            
            printf("\tackpaket tosend: %s \n",mydatenpaket->data);
            transmit_data(2,mydatenpaket);
            printf("\tackpaket sent: %s \n",mydatenpaket->data);
            printf("\tackqid sent: %c \n",mydatenpaket->qid);
            printf("\tackzid sent: %c \n",mydatenpaket->zid);
            printf("\tackcrc sent: 0x%x \n",mydatenpaket->crc);       
        } else printf("\tERROR Empfänger %d: \n",i);
        i++;
        fflush(0);
        //waitus(300000);
        
    }
    free(ptr_string);
    bcm2835_spi_end();

    return 0;
}
