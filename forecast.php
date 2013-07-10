<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>
    Datalogger Weather Forecast
    </title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
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
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="assets/ico/apple-touch-icon-144-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="assets/ico/apple-touch-icon-114-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="assets/ico/apple-touch-icon-72-precomposed.png">
    <link rel="apple-touch-icon-precomposed" href="assets/ico/apple-touch-icon-57-precomposed.png">
    <style>
    </style>
  </head>
  <body>
    
    <div class="navbar navbar-inverse navbar-fixed-top">
      <div class="navbar-inner">
        <div class="container-fluid">
          <button type="button" class="btn btn-navbar">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
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
              <li class="nav-header">NAVIGATION</li>
              <li><a href="index.html">Webcam</a></li>
              <li><a href="graph.html">Graph</a></li>
              <li class="active"><a href="forecast.php">Forecast</a></li>
              <li><a href="system.php">System</a></li>
              <li><a href="video.html">Timelapse Video</a></li>
              <li></li>
              <li></li>
              <li></li>
              <li></li>
              <li></li>
              <li class="nav-header">SUPPORT</li>
              <li><a href="mailto:kairiedel@yahoo.de">Contact</a></li>
            </ul>
          </div><!--/.well -->
        </div><!--/span-->
        <div class="span9">
          
          <div class="row-fluid">
            <!--/span-->
            <!--/span-->
            <!--/span-->
          </div><!--/row-->
          <div class="row-fluid">
            <!--/span-->
            <!--/span-->
            <div class="span4">
              <?php
                $code = "12831824";
                $ort = "http://weather.yahooapis.com/forecastrss?w=$code&u=c";
                include('wetter-widget.php'); ?>
            </div><!--/span-->
          </div><!--/row-->
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
    <script src="jquery-1.10.1.min.js">
    </script>
    <script src="assets/js/bootstrap.min.js">
    </script>
    <script>

    </script>
  </body>
</html>
