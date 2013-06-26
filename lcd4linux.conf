#  $Id: lcd4linux.conf.sample 1133 2010-11-19 09:02:34Z harbaum $
#  $URL: https://ssl.bulix.org/svn/lcd4linux/trunk/lcd4linux.conf.sample $


Variables {
   tick 500
   tack 100
   minute 60000
}

Display DPF {
    Driver     'DPF'
    Port       'usb0'
    Font       '6x8'
    Foreground '00ff00'
    Background '000000'
    Basecolor  '003D66'
}

#this example prints the second argument of signal# 0
#(in this case it prints the message displayed)
Widget Pidgin {
    class 'Text'
    expression dbus::argument(0, 1) . ': ' . dbus::argument(0, 2))
    width 20  
    align 'R'	
    event 'got_im'	
}	

Widget Time {
    class 'Text'
    expression strftime('%d.%m.%y, %H:%M:%S',time())
    width 20
    align 'L'
    update 1000
}

Widget OS {
    class 'Text'
    expression '*** '.uname('sysname').' '.uname('release').' ***'
    width 20
    align 'M'
    style 'bold'
    speed 200	
    update tick
}

Widget CPU {
    class  'Text'
    expression  uname('machine')
    prefix 'CPU '
    width  9
    align  'L'
    style test::onoff(7)>0?'bold':'norm'
    update tick
}


Widget CPUinfo {
    class  'Text'
    expression  cpuinfo('model name')
    prefix ''
    width  20
    align  'M'
    speed 100	
    update tick
}


Widget RAM {
    class  'Text'
    expression meminfo('MemTotal')/1024
    postfix ' MB RAM'	
    width  11
    precision 0
    align  'R'
    update tick
}

Widget Busy {
    class 'Text'
    expression proc_stat::cpu('busy', 500)
    prefix 'Busy'	
    postfix '%'	
    width 9	
    precision 1
    align 'R'	
    update tick	
}	

Widget BusyBar {
    class 'Bar'
    expression  proc_stat::cpu('busy',   500)
    expression2 proc_stat::cpu('system', 500)
    length 10	
    direction 'E'
    update tack
}

Widget Load {
    class 'Text'
    expression loadavg(1)
    prefix 'Load'
    postfix loadavg(1)>1.0?'!':' '
    width 10
    precision 1
    align 'R'
    update tick
}

Widget LoadBar {
    class 'Bar'
    expression  loadavg(1)
    max 2.0
    length 10	
    direction 'E'
    update tack
}


Widget Disk {
    class 'Text'
    # disk.[rw]blk return blocks, we assume a blocksize of 512
    # to get the number in kB/s we would do blk*512/1024, which is blk/2 
    # expression (proc_stat::disk('.*', 'rblk', 500)+proc_stat::disk('.*', 'wblk', 500))/2
    # with kernel 2.6, disk_io disappeared from /proc/stat but moved to /proc/diskstat
    # therefore you have to use another function called 'diskstats':
    expression diskstats('hd.', 'read_sectors', 500) + diskstats('hd.', 'write_sectors', 500)
    prefix 'disk'
    postfix ' '
    width 10	
    precision 0
    align 'R'	
    update tick	
}	

Widget DiskBar {
    class 'Bar'
    #expression  proc_stat::disk('.*', 'rblk', 500)
    #expression2 proc_stat::disk('.*', 'wblk', 500)
    # for kernel 2.6:
    expression  diskstats('hd.', 'read_sectors',  500)
    expression2 diskstats('hd.', 'write_sectors', 500)
    length 14	
    direction 'E'
    update tack
}

Widget Eth0 {
    class 'Text'
    expression (netdev('eth0', 'Rx_bytes', 500)+netdev('eth0', 'Tx_bytes', 500))/1024
    prefix 'eth0'
    postfix ' '
    width 10	
    precision 0
    align 'R'	
    update tick	
}	

Widget Eth0Bar {
    class 'Bar'
    expression  netdev('eth0', 'Rx_bytes', 500)
    expression2 netdev('eth0', 'Tx_bytes', 500)
    length 14	
    direction 'E'
    update tack
}

Widget PPP {
    class 'Text'
    expression (ppp('Rx:0', 500)+ppp('Tx:0', 500))
    prefix 'PPP'
    width 9
    precision 0
    align 'R'
    update tick
}

Widget Temp {
    class 'Text'
    expression i2c_sensors('temp_input3')*1.0324-67
    prefix 'Temp'
    width 9
    precision 1
    align 'R'
    update tick
}

Widget TempBar {
    class 'Bar'
    expression  i2c_sensors('temp_input3')*1.0324-67
    min 40
    max 80
    length 10
    direction 'E'
    update tack
}

Widget MySQLtest1 {
    class 'Text'
    expression MySQL::query('SELECT id FROM table1')
    width 20
    align 'R'
    prefix 'MySQL test:'
    update minute
}

Widget MySQLtest2 {
    class 'Text'
    expression MySQL::status()
    width 20
    align 'M'
    prefix 'Status: '
    update minute
}

Widget Uptime {
    class 'Text'
    expression uptime('%d days %H:%M:%S')
    width 30
    align 'R'
    prefix 'Uptime '
    update 1000
}

Widget mpris_TrackPosition_bar {
    class 'Bar'
    expression  mpris_dbus::method_PositionGet('org.kde.amarok')
    length 40   
    min 0
    max 100
    direction 'E'
    style 'H'
    update 200
}

# debugging widgets 

Widget BarTest {
    class 'Bar'
    # test::bar(barno,maxval,startval,delta) - move a test value between 0 and max.
    # delta= step to change value by each time it's read.
    # barno - ten different test bar values can be set up, with barno=0..9
    # if delta=0, just returns the value of bar n instead of changing it.
    expression test::bar(0,30,25,1)
    expression2 test::bar(1,30,0,1)
    length 8
    # max 50
    direction 'E'
    update 10
}

Widget BarTestVal {
    class 'Text'
    expression test::bar(0,100,50,0)
    prefix 'Test '
    width 9
    update 200
}

Widget LightningTest {
    class 'icon'
    speed 500
    visible test::onoff(0)
    bitmap {
        row1 '...***'
        row2 '..***.'
        row3 '.***..'
        row4 '.****.'
        row5 '..**..'
        row6 '.**...'
        row7 '**....'
        row8 '*.....'
    }
}


# Icons

Widget Heartbeat {
    class 'Icon'
    speed 800
    Bitmap {
	Row1 '.....|.....'
	Row2 '.*.*.|.*.*.'
	Row3 '*****|*.*.*'
	Row4 '*****|*...*'
	Row5 '.***.|.*.*.'
	Row6 '.***.|.*.*.'
	Row7 '..*..|..*..'
	Row8 '.....|.....'
    }
}

Widget EKG {
    class 'Icon'
    speed 50
    Bitmap {
	Row1 '.....|.....|.....|.....|.....|.....|.....|.....'
	Row2 '.....|....*|...*.|..*..|.*...|*....|.....|.....'
	Row3 '.....|....*|...*.|..*..|.*...|*....|.....|.....'
	Row4 '.....|....*|...**|..**.|.**..|**...|*....|.....'
	Row5 '.....|....*|...**|..**.|.**..|**...|*....|.....'
	Row6 '.....|....*|...*.|..*.*|.*.*.|*.*..|.*...|*....'
	Row7 '*****|*****|****.|***..|**..*|*..**|..***|.****'
	Row8 '.....|.....|.....|.....|.....|.....|.....|.....'
    }
}
Widget Karo {
    class 'Icon'
    speed 200
    Bitmap {
	Row1 '.....|.....|.....|.....|..*..|.....|.....|.....'
	Row2 '.....|.....|.....|..*..|.*.*.|..*..|.....|.....'
	Row3 '.....|.....|..*..|.*.*.|*...*|.*.*.|..*..|.....'
	Row4 '.....|..*..|.*.*.|*...*|.....|*...*|.*.*.|..*..'
	Row5 '.....|.....|..*..|.*.*.|*...*|.*.*.|..*..|.....'
	Row6 '.....|.....|.....|..*..|.*.*.|..*..|.....|.....'
	Row7 '.....|.....|.....|.....|..*..|.....|.....|.....'
	Row8 '.....|.....|.....|.....|.....|.....|.....|.....'
    }
}
Widget Heart {
    class 'Icon'
    speed 250
    Bitmap {
	Row1 '.....|.....|.....|.....|.....|.....'
	Row2 '.*.*.|.....|.*.*.|.....|.....|.....'
	Row3 '*****|.*.*.|*****|.*.*.|.*.*.|.*.*.'
	Row4 '*****|.***.|*****|.***.|.***.|.***.'
	Row5 '.***.|.***.|.***.|.***.|.***.|.***.'
	Row6 '.***.|..*..|.***.|..*..|..*..|..*..'
	Row7 '..*..|.....|..*..|.....|.....|.....'
	Row8 '.....|.....|.....|.....|.....|.....'
    }
}
Widget Blob {
    class 'Icon'
    speed 250
    Bitmap {
	Row1 '.....|.....|.....'
	Row2 '.....|.....|.***.'
	Row3 '.....|.***.|*...*'
	Row4 '..*..|.*.*.|*...*'
	Row5 '.....|.***.|*...*'
	Row6 '.....|.....|.***.'
	Row7 '.....|.....|.....'
	Row8 '.....|.....|.....'
    }
}
Widget Wave {
    class 'Icon'
    speed 100
    Bitmap {
	Row1 '..**.|.**..|**...|*....|.....|.....|.....|.....|....*|...**'
	Row2 '.*..*|*..*.|..*..|.*...|*....|.....|.....|....*|...*.|..*..'
	Row3 '*....|....*|...*.|..*..|.*...|*....|....*|...*.|..*..|.*...'
	Row4 '*....|....*|...*.|..*..|.*...|*....|....*|...*.|..*..|.*...'
	Row5 '*....|....*|...*.|..*..|.*...|*....|....*|...*.|..*..|.*...'
	Row6 '.....|.....|....*|...*.|..*..|.*..*|*..*.|..*..|.*...|*....'
	Row7 '.....|.....|.....|....*|...**|..**.|.**..|**...|*....|.....'
	Row8 '.....|.....|.....|.....|.....|.....|.....|.....|.....|.....'
    }
}
Widget Squirrel {
    class 'Icon'
    speed 100
    Bitmap {
	Row1 '.....|.....|.....|.....|.....|.....'
	Row2 '.....|.....|.....|.....|.....|.....'
	Row3 '.....|.....|.....|.....|.....|.....'
	Row4 '**...|.**..|..**.|...**|....*|.....'
	Row5 '*****|*****|*****|*****|*****|*****'
	Row6 '...**|..**.|.**..|**...|*....|.....'
	Row7 '.....|.....|.....|.....|.....|.....'
	Row8 '.....|.....|.....|.....|.....|.....'
    }
}

Widget Lightning {
    class 'icon'
    speed 100
    visible cpu('busy', 500)-50
    bitmap {
        row1 '...***'
        row2 '..***.'
        row3 '.***..'
        row4 '.****.'
        row5 '..**..'
        row6 '.**...'
        row7 '**....'
        row8 '*.....'
    }
}

Widget Rain {
    class 'icon'
    speed 200
    bitmap {
	row1 '...*.|.....|.....|.*...|....*|..*..|.....|*....'
	row2 '*....|...*.|.....|.....|.*...|....*|..*..|.....'
	row3 '.....|*....|...*.|.....|.....|.*...|....*|..*..'
	row4 '..*..|.....|*....|...*.|.....|.....|.*...|....*'
	row5 '....*|..*..|.....|*....|...*.|.....|.....|.*...'
	row6 '.*...|....*|..*..|.....|*....|...*.|.....|.....'
	row7 '.....|.*...|....*|..*..|.....|*....|...*.|.....'
	row8 '.....|.....|.*...|....*|..*..|.....|*....|...*.'
    }
}

Widget Timer {
    class 'Icon'
    speed 50
    Bitmap {
	Row1 '.....|.....|.....|.....|.....|.....|.....|.....|.....|.....|.....|.....|.....|.....|.....|.....|.....|.....|.....|.....|.....|.....|.....|.....|'
	Row2 '.***.|.*+*.|.*++.|.*++.|.*++.|.*++.|.*++.|.*++.|.*++.|.*++.|.*++.|.*++.|.+++.|.+*+.|.+**.|.+**.|.+**.|.+**.|.+**.|.+**.|.+**.|.+**.|.+**.|.+**.|'
	Row3 '*****|**+**|**++*|**+++|**++.|**++.|**+++|**+++|**+++|**+++|**+++|+++++|+++++|++*++|++**+|++***|++**.|++**.|++***|++***|++***|++***|++***|*****|'
	Row4 '*****|**+**|**+**|**+**|**+++|**+++|**+++|**+++|**+++|**+++|+++++|+++++|+++++|++*++|++*++|++*++|++***|++***|++***|++***|++***|++***|*****|*****|'
	Row5 '*****|*****|*****|*****|*****|***++|***++|**+++|*++++|+++++|+++++|+++++|+++++|+++++|+++++|+++++|+++++|+++**|+++**|++***|+****|*****|*****|*****|'
	Row6 '.***.|.***.|.***.|.***.|.***.|.***.|.**+.|.*++.|.+++.|.+++.|.+++.|.+++.|.+++.|.+++.|.+++.|.+++.|.+++.|.+++.|.++*.|.+**.|.***.|.***.|.***.|.***.|'
	Row7 '.....|.....|.....|.....|.....|.....|.....|.....|.....|.....|.....|.....|.....|.....|.....|.....|.....|.....|.....|.....|.....|.....|.....|.....|'
	Row8 '.....|.....|.....|.....|.....|.....|.....|.....|.....|.....|.....|.....|.....|.....|.....|.....|.....|.....|.....|.....|.....|.....|.....|.....|'


    }
}

Widget Test {
    class 'Text'
    expression '1234567890123456789012345678901234567890'
    width 40
    foreground 'ff0000ff'
}

Widget Test1 {
    class 'Text'
    expression 'ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890'
    width 40
    align 'M'
    speed 100
}

Widget Test2 {
    class 'Text'
    expression '1234567890abcdefghijklmnopqrstuvwxyz'
    width 40
    align 'M'
    speed 150
}

Widget GPO_Val1 {
    class 'Text'
    expression LCD::GPO(1)
    prefix 'GPO#1'
    width 10
    precision 0
    align 'R'
    update tick
}

Widget GPI_Val1 {
    class 'Text'
    expression LCD::GPI(1)
    prefix 'GPI#1'
    width 10
    precision 0
    align 'R'
    update tick
}

Widget GPO_Val4 {
    class 'Text'
    expression LCD::GPO(4)
    prefix 'GPO#4'
    width 10
    precision 0
    align 'R'
    update tick
}

Widget GPO_Test1 {
    class 'GPO'
    expression 255*test::onoff(1)
    update 300
}

Widget GPO_Test255 {
    class 'GPO'
    expression test::bar(0,255, 0, 1)
    update 100
}

Widget IMAGE {
    class    'Image'
    file     '/home/pi/test.png'
    update   5000
    reload   1
    visible  1
    inverted 0
}

Widget IPaddress {
    class  'Text'
    expression netinfo::ipaddr('eth0').netinfo::netmask_short('eth0')
    prefix 'IP'
    width  20
    align  'R'
    update minute
}

Layout Default {
    Row1 {
	Col1  'OS'
    }
    Row2 {
	Col1  'CPU'
	Col10 'RAM'
    }
    Row3 {
	Col1  'Busy'
	Col10 'Rain'
	Col11 'BusyBar'
    }
    Row4 {
	Col1 'Load'
	Col11 'LoadBar'
    }
    Row5 {
	Col1  'Disk'
	Col11 'DiskBar'
    }
    Row6 {
	Col1  'Eth0'
	Col11 'Eth0Bar'
    }
}

Layout Time {
    Layer 2 {
	X1.Y1 'IMAGE'
    }
}

Layout Time2 {
    Row28 {
	Col30 'RAM'
    }
    Row28 {
	Col1  'Busy'
    }
    Row29 {
	Col30  'Time'
    }
    Row29 {
    	Col1 'IPaddress'
    }
    Row30 {
	Col1 'Uptime'
    }
    Layer 2 {
	X1.Y1 'IMAGE'
    }
}

Layout TestImage {
    Layer 2 {
	X1.Y1 'IMAGE'
    }
}

Layout Test {
    Row01.Col1 'Test1'
    Row02.Col1 'Test1'
    Row03.Col1 'Test1'
    Row04.Col1 'Test1'
    Row05.Col1 'Test1'
    Row06.Col1 'Test1'
    Row07.Col1 'Test1'
    Row08.Col1 'Test1'
    Row09.Col1 'Test1'
    Row10.Col1 'Test1'
    Row11.Col1 'Test1'
    Row12.Col1 'Test1'
    Row13.Col1 'Test1'
    Row14.Col1 'Test1'
    Row15.Col1 'Test1'
    Row16.Col1 'Test1'
    Row17.Col1 'Test1'
    Row18.Col1 'Test1'
    Row19.Col1 'Test1'
    Row20.Col1 'Test1'
    Row21.Col1 'Test1'
    Row22.Col1 'Test1'
    Row23.Col1 'Test1'
    Row24.Col1 'Test1'
}

Layout TestGPO {
    Row1.Col1  'GPO_Val1'
    Row1.Col10 'GPI_Val1'
    Row2.Col1  'GPO_Val4'
    GPO1       'GPO_Test255'
    GPO4       'GPO_Test1'
}

Layout TestIcons {
    Row1.Col1 'Timer'
    Row1.Col2 'Rain'
    Row1.Col3 'Squirrel'
    Row1.Col4 'Wave'
    Row1.Col5 'Blob'
    Row1.Col6 'Heart'
    Row1.Col7 'Karo'
    Row1.Col8 'EKG'
}

Layout Dockstar{
    Row01.Col01  'FIRST'
}
 
Display 'DPF'

#Layout 'Dockstar'
#Layout 'Default'
Layout 'Time'
#Layout 'TestImage'
#Layout 'L8x2'
#Layout 'L16x1'
#Layout 'L16x2'
#Layout 'L20x2'
#Layout 'L40x2'
#Layout 'Test'
#Layout 'Test2'
#Layout 'TestGPO'
#Layout 'Debug'
#Layout 'TestIcons'
