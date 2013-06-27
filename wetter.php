<!DOCTYPE HTML>
<html>
	<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>Webcam and Weather</title>
  <meta name="description" content="Webcam and plot of temperature and humidity data from wunderground and DS18B20 sensor connected to Raspberry Pi">
	<meta name="author" content="Kai Riedel">
	<meta name="version" content="1.0">
	<script type="text/javascript" src="jquery-1.10.1.min.js"></script>
	<script type="text/javascript" src="highstock.js"></script>
  <script type="text/javascript" src="gray.js"></script>
	<script type="text/javascript">

$(function() {
	$.getJSON('data.json', function(data) {

		// split the data set into ohlc and volume
		var first = [],
			second = [],
      third  = [],
			dataLength = data.length;
		//	alert (data[0][1]);
		for (i = 0; i < dataLength; i++) {
			first.push([
				data[i][0], // the date
				data[i][1], // open
       ]);
			
			second.push([
				data[i][0], // the date
				data[i][2] // the volume
			]);
      
      third.push([
        data[i][0],
        data[i][3]
      ])
		}
    
   
		// create the chart
		$('#container').highcharts('StockChart', {
		    rangeSelector: {
		        selected: 1
		    },
        chart: {
                zoomType: 'xy'
            },
        
		    title: {
		        text: 'Wetterdaten Auswertung'
		    },
         subtitle: {
                text: 'Quelle: www.wunderground.com, DS18B20'
            },
          tooltip: {
                shared: true
            },
            legend: {
                verticalAlign: 'bottom',
                floating: false,
                enabled: true
            },

		    yAxis: [{
		        labels: {
                    formatter: function() {
                        return this.value +' \u00B0C';
                    },
                    style: {
                        color: '#89A54E'
                    }
                },
                title: {
                    text: 'Temperatur',
                    style: {
                        color: '#89A54E'
                    }
                },
             }, {
		        // Secondary yAxis
                gridLineWidth: 0,
                title: {
                    text: 'Luftfeuchte',
                    style: {
                        color: '#4572A7'
                    }
                },
                labels: {
                    formatter: function() {
                        return this.value +' %';
                    },
                    style: {
                        color: '#4572A7'
                    }
                },
                 opposite: true
                 }
		    ],
        
		    
		    series: [{
		        name: 'Temperatur Aussen',
            type: 'line',
		        data: first,
            tooltip: {
		        	valueDecimals: 1,
              xDateFormat: '%d.%m.%Y, %H:%M:%S',
              valueSuffix: ' \u00B0C'
		        }
		        }, 
            {
		        name: 'Luftfeuchte Aussen',
            type: 'line',
		        data: second,
		        yAxis: 1,
            tooltip: {
		        	valueDecimals: 1,
              xDateFormat: '%d.%m.%Y, %H:%M:%S',
              valueSuffix: ' %'
		        }
            },
            {
             name: 'Temperatur Innen',
            type: 'line',
		        data: third,
		        yAxis: 0,
            tooltip: {
		        	valueDecimals: 1,
              xDateFormat: '%d.%m.%Y, %H:%M:%S',
              valueSuffix: ' \u00B0C'
            }
		        }]
		});
	});
});
	</script>
	</head>
<body>
<?php
$code = "12831824";
$ort = "http://weather.yahooapis.com/forecastrss?w=$code&u=c";
include('wetter-widget.php'); ?>
<h3>Wetterstation</h3>
<hr>
<p>Aktuelles Bild der Webcam:</p> 
<img src="./media/image.jpg" width="640" height="480"/>
<p>Letzte Zeitraffer Aufnahme:
<a href="./media/outfile.mp4">Timelapse video file</a></p>
<p>Systeminfo:
<a href="system.php">Systeminfo</a></p>
<p></p>
<div id="container" style="min-width:80%; height:500px;  margin: 0 auto"></div>
<hr>
</body>
</html>
