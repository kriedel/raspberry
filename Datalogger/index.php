<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>
   	 Datalogger Webcam
    </title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Datalogger Webcam View">
    <meta name="author" content="Riedel">
    <!-- Le styles -->
    <link href="assets/css/bootstrap.min.css" rel="stylesheet">
    <style>
      body { padding-top: 60px; /* 60px to make the container go all the way
      to the bottom of the topbar */ }
    </style>
   <link href="assets/css/bootstrap-responsive.css" rel="stylesheet">
    <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js">
      </script>
    <![endif]-->
    <!-- Le fav and touch icons -->
    <link rel="shortcut icon" href="assets/ico/favicon.ico">
    <script src="jquery-1.10.1.min.js">
    </script>
    <script src="assets/js/bootstrap.min.js">
    </script>
   <script type="text/javascript"> 

// get data from json file
$(function() {
	$.getJSON('data.json', function(data) {
		// split the data set
       $("#balcony").html(data[data.length-1][1]+" °C");
       $("#bathroom").html(data[data.length-1][3]+" °C");
       $("#inside").html(data[data.length-1][4]+" °C");
       $("#humidity").html(data[data.length-1][2]+" %");
       $("#outside").html(data[data.length-1][5]+ " °C");
		})

});
    </script>
    </head>
  <body>
    
    <div class="navbar navbar-inverse navbar-fixed-top">
      <div class="navbar-inner">
        <div class="container-fluid">
          <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
          </a>
          <a class="brand" href="index.html">Datalogger</a>
          <div class="nav-collapse collapse">
            <p class="navbar-text pull-right">
            </p>
            <ul class="nav">
              <li class="active"><a href="index.html">Home</a></li>
              <li><a href="about.html">About</a></li>
              <li><a href="contact.html">Contact</a></li>
	    </ul>
          </div><!--/.nav-collapse -->
        </div>
      </div>
    </div>
    
    <div class="container-fluid">
      <div class="row-fluid">
        <div class="span3">
          <div class="well sidebar-nav">
            <ul class="nav nav-list">
              <li class="nav-header active">NAVIGATION</li>
              <li class="active"><a href="index.html">Webcam</a></li>
              <li><a href="graph.html">Graph</a></li>
              <li><a href="forecast.php">Forecast</a></li>
              <li><a href="system.php">System</a></li>
              <li><a href="video.html">Timelapse Video</a></li>
              <li></li>
              <li class="nav-header">FTP</li>
              <li><a href="ftp://ndl.dyns.cx">FTP Server</a></li>
            </ul>
          </div><!--/.well -->
            
       <h5>Actual Measuring Values</h5>
 
      <!-- Tabelle mit abwechselnder Zellenhintergrundfarbe und Außenrahmen -->
      <table class="table table-striped table-bordered">
        <thead>
          <tr>
            <th>Place</th>
            <th>Value</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>Balcony</td>
            <td id="balcony"></td>
          </tr>
          <tr>
            <td>Bathroom</td>
            <td id="bathroom"></td>
          </tr>
          <tr>
            <td>Inside</td>
            <td id="inside"></td>
          </tr>
          <tr>
            <td>Humidity outside</td>
            <td id=humidity></td>
          </tr>
          <tr>
            <td>Outside Global</td>
            <td id=outside></td>
          </tr>
        </tbody>
      </table>
            
        </div><!--/span-->
        <div class="span9">
          <?php
               include('snap.php'); ?>
          <img alt="No image found" src="./media/image.jpg" width="720" class="img-rounded">
	</div><!--/span-->
    
     </div><!--/row-->
    
      <hr>
    
      <footer>
        <p>© K. Riedel 2013</p>
      </footer>
    </div><!--/.fluid-container-->
    

    <style>
      
      /* To push content below navbar */
      @media (min-width: 980px) {
        body {
          margin-top: 41px;
        }
      }
      
      .sidebar-nav {
        padding: 9px 0;
      }
      
      @media (max-width: 980px) {
        /* Enable use of floated navbar text */
        .navbar-text.pull-right {
          float: none;
          padding-left: 5px;
          padding-right: 5px;
        }
      }
     
    </style>
   </body>
</html>
