 /** @file rfm12b.h
 *   
 * @author Peter Emmerling 
 * 
 * @date 31.01.2013 
 * 
 * @version 0.7 
 * basic defines, data structures,and function prototypes
 * used from both, transmitter and receiver
 * 
 */

#include <stdio.h>
#include <stdlib.h>
#include <unistd.h>
#include <string.h>
#include <stdint.h>

#ifndef _STDBOOL_H
#define _STDBOOL_H
#define bool _Bool //Bool als Datentyp hinzufügen
#define true 1
#define false 0
#define __bool_true_false_are_defined 1

#endif

#define TIMEOUT 1000 //Timeout for transmitting data
#define TIMEOUT_ACK 500000 //Timeout for waiting for acknowledge
#define Master 0
#define Slave 1
typedef enum { MASTER, SLAVE } geraetetyp;

struct struct_datenpaket
{
    char data[14];
    char qid;
    char zid;
    char crc;
};

typedef struct struct_datenpaket datenpaket;

#define MaxTeilnehmer 3 //maximale Teilnehmeranzahl (höchstens 9)

#define RFM_IRQ 22			//IRQ GPIO pin. entspricht RPI_V2_GPIO_P1_15
#define RFM_CE BCM2835_SPI_CS1  //SPI chip select


#define GERAETID '1' //ID des Gerätes (eindeutig)
#define GERAETETYP Master //definiert ob Master oder Slave
#define dataToSend "XxXxXxXxXxXxXQEC"

//! Initialize device list, not used yet!
void init_device_list (void);
//! Initialize rfm12b module for 434,15 MHz modulation frequenzy and 4,8k baud rate
void rfm12b_initialize (void);
//! Calculate custom crc
unsigned int calc_crc (unsigned int, unsigned int, unsigned int);
//! Convert from char to int
int char_to_int (char);
//! Convert from int to char
char int_to_char (int);
//! Low level function for communication with the rfm12b via SPI of the RPi
uint16_t rf12_xfer (uint16_t);
//! Transmitting 16 data bytes with preamble and synchronisation word
unsigned int rf12_txdata(unsigned char*, unsigned char );
//! Receiving 16 data bytes with or without timeout functionality
unsigned int rf12_rxdata(unsigned char*, unsigned char, bool ); //Rohdaten empfangen
//! Function managing data receiption
unsigned char* receive_data(bool, unsigned int); //Rohdaten aufbereiten
//! Function managing data transmission
void transmit_data (unsigned short, datenpaket*);
//! Initialize SPI of the Raspberry Pi using bcm2835-library
void spi_init (void);
//! Wait microseconds using the usleep-function
void waitus (unsigned int);
//! Wait for nIRQ getting LOW or timeout
unsigned int wait_rfm12(unsigned int);
//! Control FIFO (receiver) of rfm12b
void activate_receiver( void);
//! Control FIFO (receiver) of rfm12b
void Deactivate_receiver( void);

