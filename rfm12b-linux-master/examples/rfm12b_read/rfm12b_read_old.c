/* rfm12b-linux: linux kernel driver for the rfm12(b) RF module by HopeRF
*  Copyright (C) 2013 Georg Kaindl
/*
 ============================================================================
 Name        : url.c
 Author      : Kai Riedel
 Version     : 1.0
 Copyright   :
 Description : receive program for RFM12 modules
 ============================================================================
 */

//first install libcurl package
//sudo apt-get install libcurl4-openssl-dev
//compile with: use make in ../ directory

#include <stdio.h>
#include <stdlib.h>
#include <fcntl.h>
#include <string.h>
#include <time.h>
#include <signal.h>
#include <errno.h>
#include <curl/curl.h>

#include "../common/common.h"
#include "../../rfm12b_config.h"
#include "../../rfm12b_ioctl.h"

#define RF12_BUF_LEN   128

float temperature;
char humidity;

static volatile int running;

void sig_handler(int signum)
{
   signal(signum, SIG_IGN);
   running = 0;
}

size_t write_data(void *ptr, size_t size, size_t count, void *stream)
{
 
  char * pch;
  pch = strstr (ptr,"Temperature");
  strncpy ( pch, &pch[12], 5 );
  //puts (tch);
  temperature = atof(pch);
  //printf ("%.1f\n", temperature); 
  pch = strstr (ptr,"Humidity");
  strncpy ( pch, &pch[10], 2 ); 
  //puts (pch);
  humidity = atoi(pch); 
  //printf ("%d\n", humidity);
  
  //sprintf(pch, "%s", ptr);
}

int main(int argc, char** argv)
{
   CURL *curl;
   CURLcode res;
   
   int fd, len, i;
   int band_id, group_id, bit_rate, ioctl_err;
   char *devname;
   char buf[128];
	char buf2[40];
   char buf3[40];
   unsigned long pkt_cnt;
	unsigned long time_value=0;
   time_t tt;
   
   char * url = "http://rss.wunderground.com/auto/rss_full/global/stations/10575.xml?units=metric";
     
   /** values sensor 2 */
   int8_t Sensor2Value=0;
   int8_t Sensor2Correction=-50;

    /** values sensor 3 */
   int8_t Sensor3Value=0;
   int8_t Sensor3Correction=-50;
   
   float Sensor4Value;
   
   int8_t fs;
   char *filename2 = "/home/pi/sensorvalue2";
   char *filename3 = "/home/pi/sensorvalue3";
   FILE * file;
   
   devname = RF12_TESTS_DEV;
   
   //fd = open(RF12_TESTS_DEV, O_RDWR);
   
   fd = open(RF12_TESTS_DEV,O_RDONLY);
   long flag = fcntl(fd, F_GETFL, 0 );
   fcntl(fd,F_SETFL,flag | O_NONBLOCK);
   
   if (fd < 0) {
      printf("\nfailed to open %s: %s.\n\n", devname, strerror(errno));
      return fd;
   } else
      printf(
         "\nsuccessfully opened %s as fd %i, entering read loop...\n\n",
         devname, fd
      );

   fflush(stdout);
   signal(SIGINT, sig_handler);
   signal(SIGTERM, sig_handler);
   
     // this demonstrates how to use ioctl() to read and write config data
   ioctl_err = 0;
   group_id=212;
   //ioctl_err |= ioctl(fd, RFM12B_IOCTL_SET_GROUP_ID, &group_id);
   ioctl_err |= ioctl(fd, RFM12B_IOCTL_GET_GROUP_ID, &group_id);
   ioctl_err |= ioctl(fd, RFM12B_IOCTL_GET_BAND_ID, &band_id);
   ioctl_err |= ioctl(fd, RFM12B_IOCTL_GET_BIT_RATE, &bit_rate);
   
   // and this is how to reconfigure via ioctl()... we simply write the
   // same data back that we read...
   //group_id=212;
   //ioctl_err |= ioctl(fd, RFM12B_IOCTL_SET_GROUP_ID, &group_id);
  // ioctl_err |= ioctl(fd, RFM12B_IOCTL_SET_BAND_ID, &band_id);
  // ioctl_err |= ioctl(fd, RFM12B_IOCTL_SET_BIT_RATE, &bit_rate);
   
   if (0 != ioctl_err) {
      printf("\nerror during ioctl(): %s.", strerror(errno));
      return -1;
   }
   
   printf("RFM12B configured to GROUP %i, BAND %i, BITRATE: 0x%.2x.\n\n",
      group_id, band_id, bit_rate);

   pkt_cnt = 0;
   running = 1;
   do {      
      len = read(fd, buf, RF12_BUF_LEN);
      
      time (&tt);
      
      if (time(0)>time_value+600)     // save data with interval of 10 minutes
       {
       curl = curl_easy_init();
       if(curl)
       {
         curl_easy_setopt(curl, CURLOPT_URL, url);
         curl_easy_setopt(curl, CURLOPT_FOLLOWLOCATION, 1L);
         curl_easy_setopt(curl, CURLOPT_WRITEFUNCTION, write_data);
       
         res = curl_easy_perform(curl); // get weather data from rss feed, use of write_data routine
       
         curl_easy_cleanup(curl);
        }
        
        file = fopen("/sys/bus/w1/devices/28-000002878f35/w1_slave","r");
        if (file == NULL) perror ("Error opening file");
        else
        {
        fgets (buf3, 100, file);
        fgets (buf3, 100, file);
        fclose (file);
        Sensor4Value = atof(&buf3[29])/1000;
        printf("%.1f\n", Sensor4Value);
        }
        
        file = fopen("/var/www/data.json","r+");
        if (file == NULL) perror ("Error opening file");
        else
        {
        time_value = time(0);  
   	  sprintf(buf2, "%d%s", time_value,"000");
        //printf("%s\n",buf2);
        
		  fseek(file, -1, SEEK_END);
        fprintf(file, ",\n[%s,%d,%d,%d,%.1f,%.1f]]", buf2, Sensor2Value, humidity, Sensor3Value, Sensor4Value, temperature);
        fclose(file);
        
        fflush(stdout);
        }
        }
      
      if (len > 0) {
        /* printf("%s", ctime(&tt));
         printf("\t%i bytes read\n\t\t", len);
  
         for (i=0; i<len; i++) {
            printf("%d ", buf[i]);
         }
         printf("\n");*/
         if (buf[0]=='A')					    // Sensor A
			{
            Sensor2Value = atoi(&buf[1]);		 // convert in integer
			if (Sensor2Value != 0)
    	 		Sensor2Value += Sensor2Correction;  // correct value if conversion ok
   
            //printf("Sensorvalue 2: %d \n", Sensor2Value);
            
          //  FILE * file = fopen( filename2, "w" );
          //  fprintf (file, "%d",Sensor2Value);
   		 //  fclose( file );
         }
         
          if (buf[0]=='C')					    // Sensor B
			{
            Sensor3Value = atoi(&buf[1]);		 // convert in integer
   			if (Sensor3Value != 0)
                     Sensor3Value += Sensor3Correction;  // correct value if conversion ok
   
            printf("Sensorvalue 3: %d \n", Sensor3Value);
            
          //  FILE * file = fopen( filename3, "w" );
          //  fprintf (file, "%d",Sensor3Value); 
   		 //  fclose( file );
         }
        
        fflush(stdout);
        pkt_cnt++;
      }
      else if (len < 0)
      {
        break;
      }
      
    } 
    while (running);
   
   print_stats(fd);
   
   close(fd);
   
   printf("\n\n%lu packet(s) received.\n\n", pkt_cnt);
   
   return len;
}
