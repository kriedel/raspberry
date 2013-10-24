/* rfm12b-linux: linux kernel driver for the rfm12(b) RF module by HopeRF
*  Copyright (C) 2013 Georg Kaindl
*  
*  This file is part of rfm12b-linux.
*  
*  rfm12b-linux is free software: you can redistribute it and/or modify
*  it under the terms of the GNU General Public License as published by
*  the Free Software Foundation, either version 2 of the License, or
*  (at your option) any later version.
*  
*  rfm12b-linux is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*  GNU General Public License for more details.
*  
*  You should have received a copy of the GNU General Public License
*  along with rfm12b-linux.  If not, see <http://www.gnu.org/licenses/>.
*/

/*
   DOCUMENTATION:
   
   This is a trivial example that will open the rfm12b device and
   continously read() from it in an endless loop. The individual
   bytes of the received packet will then be written to stdout.
   
   On the receiving side, the driver will block on read() until a
   packet is received. A subsequent read will then return the entire
   packet, provided your userspace buffer is large enough (if your
   buffer is too small, the rest of the bytes in the packet will be
   lost forever). Since the maximum packet length is 66 bytes (to
   ensure compatibility with the JeeNode Arduino library), you should
   provide a buffer at least of this size. The return value of read()
   behaves as expected, and if it's a positive number, it's the length
   of the packet that has been received.
   
   Press Ctrl+C (e.g. send a SIGINT) to quit the program. It will
   then print the driver statistics.
   
   You can use this example as a receiver to the rfm12b_write.c example
   or as receiver for the rfm12b_send Arduino example. It's also useful
   to test or debug your own sending code.
*/

#include <stdio.h>
#include <fcntl.h>
#include <string.h>
#include <time.h>
#include <signal.h>
#include <errno.h>

#include "../common/common.h"
#include "../../rfm12b_config.h"
#include "../../rfm12b_ioctl.h"

#define RF12_BUF_LEN   128

static volatile int running;

void sig_handler(int signum)
{
   signal(signum, SIG_IGN);
   running = 0;
}

int main(int argc, char** argv)
{
   int fd, len, i;
   int band_id, group_id, bit_rate, ioctl_err;
   char* devname, buf[128];
   char buffer[80];
   unsigned long pkt_cnt;
   time_t tt;
     
   /** values sensor 2 */
   int8_t Sensor2Value=0;
   int8_t Sensor2Correction=-50;

    /** values sensor 3 */
   int8_t Sensor3Value=0;
   int8_t Sensor3Correction=-50;
   
   int8_t fs;
   char *filename2 = "/home/pi/sensorvalue2";
   char *filename3 = "/home/pi/sensorvalue3";
   mode_t mode = S_IRUSR | S_IWUSR | S_IRGRP | S_IROTH;
   
   devname = RF12_TESTS_DEV;
   
   fd = open(RF12_TESTS_DEV, O_RDWR);
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
   ioctl_err |= ioctl(fd, RFM12B_IOCTL_SET_GROUP_ID, &group_id);
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
      
      if (len > 0) {
         /*printf("%s", ctime(&tt));
         printf("\t%i bytes read\n\t\t", len);
  
         //printf("%d\n", time(0));    // print utc time
         
         for (i=0; i<len; i++) {
            printf("%d ", buf[i]);
         }
         printf("\n");*/
         if (buf[0]=='A')					    // Sensor A
			{
            Sensor2Value = atoi(&buf[1]);		 // convert in integer
   			if (Sensor2Value != 0)
                     Sensor2Value += Sensor2Correction;  // correct value if conversion ok
   
           // printf("Sensorvalue 2: %d \n", Sensor2Value);
            
            FILE * file = fopen( filename2, "w" );
            fprintf (file, "%d",Sensor2Value);
   			fclose( file );
         }
         
          if (buf[0]=='B')					    // Sensor B
			{
            Sensor3Value = atoi(&buf[1]);		 // convert in integer
   			if (Sensor3Value != 0)
                     Sensor3Value += Sensor3Correction;  // correct value if conversion ok
   
           // printf("Sensorvalue 2: %d \n", Sensor3Value);
            
            FILE * file = fopen( filename3, "w" );
            fprintf (file, "%d",Sensor3Value);
   			fclose( file );
         }
         fflush(stdout);   
   
         pkt_cnt++;
      } else if (len < 0) {
         break;
      }      
   } while (running);
   
   print_stats(fd);
   
   close(fd);
   
   printf("\n\n%lu packet(s) received.\n\n", pkt_cnt);
   
   return len;
}
