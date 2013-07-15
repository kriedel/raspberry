<?php
require_once('php/simplepie.inc');
require_once('php/simplepie_yahoo_weather.inc');
 
$feed = new SimplePie();
 
$feed->set_feed_url($ort); 
 
$feed->set_item_class('SimplePie_Item_YWeather');
 
$feed->init();
 
$weather = $feed->get_item(0);
 
?>	

<b>Wetter f&uuml;r <?php echo $weather->get_city(); ?></b><br>
<?php echo $weather->get_last_updated('d.m.Y - H:i'); ?> Uhr
<br>
<!--Aktuelles Wetter-->
<img src="bilder/wetter/<?php echo $weather->get_condition_code(); ?>.png"><br><b>
     <?php
  if($weather->get_condition() == "Light Rain")
    {
    echo "Leichter Regen";
    }
elseif($weather->get_condition() == "Haze")
    {
    echo "Dunst";
    }
elseif($weather->get_condition() == "Unknown Precipitation")
      {
      echo "Niederschlag";
      }
// Unbekannter Niederschlag klingt doof.
  elseif($weather->get_condition() == "Partly Cloudy")
      {
      echo "Teilweise bewölkt";
      }
 elseif($weather->get_condition() == "Cloudy")
      {
      echo "Bewölkt";
      }
elseif($weather->get_condition() == "Mostly Cloudy")
      {
      echo "Überwiegend bewölkt";
      }
elseif($weather->get_condition() == "Blowing Snow")
      {
      echo "Schneetreiben";
      }
elseif($weather->get_condition() == "Drizzle")
      {
      echo "Nieselregen";
      }
elseif($weather->get_condition() == "Light Rain Shower")
      {
      echo "Leichter Regenschauer";
      }
elseif($weather->get_condition() == "Sunny")
      {
      echo "Sonnig";
      }
elseif($weather->get_condition() == "Fair")
      {
      echo "Heiter";
      }
elseif($weather->get_condition() == "Light Drizzle")
      {
      echo "Leichter Nieselregen";
      }
elseif($weather->get_condition() == "Wintry Mix")
      {
      echo "Winterlicher Mix";
      }
elseif($weather->get_condition() == "Clear")
      {
      echo "Klar";
      }
elseif($weather->get_condition() == "Light Snow")
      {
      echo "Leichter Schneefall";
      }
elseif($weather->get_condition() == "Fog")
      {
      echo "Nebel";
      }
elseif($weather->get_condition() == "Mist")
      {
      echo "Nebel";
      }
elseif($weather->get_condition() == "Showers in the Vicinity")
      {
      echo "Regenfälle in der Nähe";
      }
elseif($weather->get_condition() == "Light Rain/Windy")
      {
      echo "Leichter Regen/Windig";
      }
elseif($weather->get_condition() == "Rain and Snow")
      {
      echo "Schneeregen";
      }
elseif($weather->get_condition() == "Light Snow")
      {
      echo "Leichter Schneefall";
      }
elseif($weather->get_condition() == "Snow")
      {
      echo "Schneefall";
      }
elseif($weather->get_condition() == "Rain")
      {
      echo "Regen";
      }
elseif($weather->get_condition() == "Mostly Clear")
      {
      echo "Überwiegend Klar";
      }
elseif($weather->get_condition() == "Foggy")
      {
      echo "neblig";
      }
elseif($weather->get_condition() == "Freezing Drizzle")
      {
      echo "gefrierender Nieselregen";
      }
  elseif($weather->get_condition() == "Freezing Rain")
      {
      echo "gefrierender Regen";
      }
elseif($weather->get_condition() == "Mostly sunny")
      {
      echo "Überwiegend sonnig";
      }
elseif($weather->get_condition() == "Light Rain/Freezing Rain")
      {
      echo "Leichter Regen/Gefrierender Regen";
      }
elseif($weather->get_condition() == "Light Snow Shower")
      {
      echo "Leichter Schneeschauer";
      }
elseif($weather->get_condition() == "Ice Crystals")
      {
      echo "Eiskristalle";
      }
elseif($weather->get_condition() == "Thunder")
      {
      echo "Gewitter";
      }
elseif($weather->get_condition() == "Heavy Thunderstorm")
      {
      echo "Schweres Gewitter";
      }
elseif($weather->get_condition() == "Thunderstorm")
      {
      echo "Gewitter";
      }
  elseif($weather->get_condition() == "Heavy Rain")
      {
      echo "Starker Regen";
      }
  elseif($weather->get_condition() == "Light Rain with Thunder")
      {
      echo "Leichter Regen mit Gewitter";
      }
  elseif($weather->get_condition() == "Thunder in the Vicinity")
      {
      echo "Gewitter in der Umgebung";
      }
  elseif($weather->get_condition() == "Partly Cloudy/Windy")
      {
      echo "Teilweise bewölkt/windig";
      }
elseif($weather->get_condition() == "Light Rain Shower/Windy")
      {
      echo "Leichter Regenschauer/windig";
      }
elseif($weather->get_condition() == "Patches of Fog")
      {
      echo "Nebelfelder";
      }
elseif($weather->get_condition() == "Rain Shower")
      {
      echo "Regenschauer";
      }
elseif($weather->get_condition() == "Light Freezing Rain")
      {
      echo "Leichter gefrierender Regen";
      }
elseif($weather->get_condition() == "Rain Shower/Windy")
      {
      echo "Regenschauer/Windig";
      }
elseif($weather->get_condition() == "Mostly Cloudy/Windy")
      {
      echo "Meist wolkig/Windig";
      }
elseif($weather->get_condition() == "Snow Shower")
      {
      echo "Schneeschauer";
      }
elseif($weather->get_condition() == "Patches of Fog/Windy")
      {
      echo "Nebelfelder/Windig";
      }
elseif($weather->get_condition() == "Shallow Fog")
      {
      echo "flacher Nebel";
      }
elseif($weather->get_condition() == "Cloudy/Windy")
      {
      echo "Wolkig/Windig";
      }
elseif($weather->get_condition() == "Light Snow/Fog")
      {
      echo "Leichter Schneefall/Nebel";
      }
elseif($weather->get_condition() == "Heavy Rain Shower")
      {
      echo "Starker Regenschauer";
      }
elseif($weather->get_condition() == "Light Rain Shower/Fog")
      {
      echo "Leichter Regenschauer/Nebel";
      }
elseif($weather->get_condition() == "Rain/Windy")
      {
      echo "Regen/Windig";
      }
elseif($weather->get_condition() == "Drizzle/Windy")
      {
      echo "Nieselregen/Windig";
      }
elseif($weather->get_condition() == "Heavy Drizzle")
      {
      echo "starker Nieselregen";
      }
elseif($weather->get_condition() == "Squalls")
      {
      echo "Böen";
      }
elseif($weather->get_condition() == "Heavy Thunderstorm/Windy")
      {
      echo "Schweres Gewitter/Windig";
      }
elseif($weather->get_condition() == "Snow Grains")
      {
      echo "Schneegriesel";
      }
elseif($weather->get_condition() == "Partial Fog")
      {
      echo "teilweise Nebel";
      }
elseif($weather->get_condition() == "Snow/Windy")
      {
      echo "Schnee/Windig";
      }
elseif($weather->get_condition() == "Fair/Windy")
      {
      echo "Heiter/Windig";
      }
elseif($weather->get_condition() == "Heavy Snow/Windy")
      {
      echo "Starker Schneefall/Windig";
      }
elseif($weather->get_condition() == "Heavy Snow")
      {
      echo "Starker Schneefall";
      }
elseif($weather->get_condition() == "Light Snow Shower/Fog")
      {
      echo "Leichter Schneeschauer/Nebel";
      }
elseif($weather->get_condition() == "Heavy Snow Shower")
      {
      echo "Starker Schneeschauer";
      }
elseif($weather->get_condition() == "Light Snow/Windy")
      {
      echo "Leichter Schneeschauer/Windig";
      }
elseif($weather->get_condition() == "Drifting Snow/Windy")
      {
      echo "Schneetreiben/Windig";
      }
elseif($weather->get_condition() == "Light Snow/Windy/Fog")
      {
      echo "Leichter Schneeschauer/Windig/Nebel";
      }
elseif($weather->get_condition() == "Freezing Drizzle/Windy")
      {
      echo "Gefrierender Nieselregen/Windig";
      }
elseif($weather->get_condition() == "Light Snow/Freezing Rain")
      {
      echo "Leichter Schneefall/Gefrierender Regen";
      }
elseif($weather->get_condition() == "Light Sleet")
      {
      echo "Leichter Schneeregen";
      }
elseif($weather->get_condition() == "Light Freezing Drizzle")
      {
      echo "Leichter gefrierender Nieselregen";
      }
elseif($weather->get_condition() == "Light Snow Grains")
      {
      echo "Leichter Schneegriesel";
      }
elseif($weather->get_condition() == "Clear/Windy")
      {
      echo "Klar/Windig";
      }
elseif($weather->get_condition() == "Heavy Rain/Windy")
      {
      echo "Starker Regen/Windig";
      }
elseif($weather->get_condition() == "Fog/Windy")
      {
      echo "Nebel/Windig";
      }
elseif($weather->get_condition() == "Unknown")
      {
      echo "unbekannt";
      }
elseif($weather->get_condition() == "Sunny/Windy")
      {
      echo "Sonnig/Windig";
      }
elseif($weather->get_condition() == "Sleet and Freezing Rain")
      {
      echo "Schneeregen und gefrierender Regen";
      }
elseif($weather->get_condition() == "Clear/Windy")
      {
      echo "Klar/Windig";
      }
elseif($weather->get_condition() == "Thunderstorm/Windy")
      {
      echo "Gewitter/Windig";
      }
elseif($weather->get_condition() == "Light Snow with Thunder")
      {
      echo "Leichter Schneefall mit Gewitter";
      }
elseif($weather->get_condition() == "Light Rain/Fog")
      {
      echo "Leichter Regen/Nebel";
      }
elseif($weather->get_condition() == "Light Snow/Windy/Fog")
      {
      echo "Leichter Schneefall/Windig/Nebel";
      }
elseif($weather->get_condition() == "Sleet/Windy")
      {
      echo "Schneeregen/Windig";
      }
elseif($weather->get_condition() == "Rain and Snow/Windy ")
      {
      echo "Regen und Schnee/Windig";
      }
elseif($weather->get_condition() == "Fog/Windy")
      {
      echo "Nebel/Windig";
      }
elseif($weather->get_condition() == "Rain and Snow/Windy")
      {
      echo "Regen und Schnee/Windig";
      }
elseif($weather->get_condition() == "Light Freezing Rain/Fog")
      {
      echo "Leichter gefrierender Regen/Nebel";
      }
elseif($weather->get_condition() == "Drifting Snow")
      {
      echo "Schneetreiben";
      }
    else
      {
      echo $weather->get_condition();
      }   
  ?> bei
 <?php echo $weather->get_temperature(); ?>&deg;<?php echo $weather->get_units_temp(); ?></b><br> gefühlt wie <?php echo $weather->get_wind_chill(); ?>&deg;<?php echo $weather->get_units_temp(); ?><br>
Wind: <?php echo $weather->get_wind_speed(); ?> <?php echo $weather->get_units_speed(); ?> aus <?php echo $weather->get_wind_direction(); ?><br>
Feuchtigkeit: <?php echo $weather->get_humidity(); ?>%<br>
Luftdruck: <?php echo $weather->get_pressure(); ?> <?php echo $weather->get_units_pressure(); ?>
, <?php
  if($weather->get_rising() == "0")
    {
    echo "konstant";
    }
 elseif($weather->get_rising() == "1")
      {
      echo "steigend";
      }
    else
if($weather->get_rising() == "2")
      {
      echo "fallend";
      }
  ?>




<?php
  if($weather->get_visibility() > 50)
    {
    echo "<br>Sichtweite: uneingeschränkt";
    }
elseif($weather->get_visibility() == "9.99")
    {
    echo " ";
}
elseif($weather->get_visibility() == "")
    {
    echo " ";
}
  else
    {
    echo "<br>Sichtweite: ",$weather->get_visibility()," km";
    }
  ?>


<br>
Sonnenaufgang: <?php echo $weather->get_sunrise(); ?> <br>
Sonnenuntergang: <?php echo $weather->get_sunset(); ?> <br>
<br>
<!--Vorhersage-->
		<?php foreach ($weather->get_forecasts() as $forecast): ?>
 <img style="float: left;" src="bilder/wetter/small/<?php echo $forecast->get_code(); ?>.png">
		<?php echo $forecast->get_date('d.m.Y'); ?>: <?php echo $forecast->get_low(); ?> / <?php echo $forecast->get_high(); ?>&deg;<?php echo $weather->get_units_temp(); ?><br>

    <?php
  if($forecast->get_label() == "Light Rain")
    {
    echo "Leichter Regen";
    }
  elseif($forecast->get_label() == "Partly Cloudy")
      {
      echo "Teilweise bewölkt";
      }
 elseif($forecast->get_label() == "Cloudy")
      {
      echo "Bewölkt";
      }
 elseif($forecast->get_label() == "Haze")
      {
      echo "Dunst";
      }
elseif($forecast->get_label() == "Mostly Cloudy")
      {
      echo "Überwiegend bewölkt";
      }
elseif($forecast->get_label() == "Blowing Snow")
      {
      echo "Schneetreiben";
      }
elseif($forecast->get_label() == "Light Snow Late")
      {
      echo "Später leichter Schneefall";
      }
elseif($forecast->get_label() == "Drizzle")
      {
      echo "Nieselregen";
      }
elseif($forecast->get_label() == "Light Rain Shower")
      {
      echo "Leichter Regenschauer";
      }
elseif($forecast->get_label() == "Sunny")
      {
      echo "Sonnig";
      }
elseif($forecast->get_label() == "Fair")
      {
      echo "Heiter";
      }
elseif($forecast->get_label() == "Light Drizzle")
      {
      echo "Leichter Nieselregen";
      }
elseif($forecast->get_label() == "Wintry Mix")
      {
      echo "Winterlicher Mix";
      }
elseif($forecast->get_label() == "Clear")
      {
      echo "Klar";
      }
elseif($forecast->get_label() == "Light Snow/Fog")
      {
      echo "leichter Schneefall/Nebel";
      }
elseif($forecast->get_label() == "Light Snow")
      {
      echo "Leichter Schneefall";
      }
elseif($forecast->get_label() == "Light Snow Early")
      {
      echo "früh leichter Schneefall";
      }
elseif($forecast->get_label() == "Fog")
      {
      echo "Nebel";
      }
elseif($forecast->get_label() == "Mist")
      {
      echo "Nebel";
      }
elseif($forecast->get_label() == "Snow Showers")
      {
      echo "Schneeschauer";
      }
elseif($forecast->get_label() == "Showers in the Vicinity")
      {
      echo "Regenfälle in der Nähe";
      }
elseif($forecast->get_label() == "Light Rain/Windy")
      {
      echo "Leichter Regen/Windig";
      }
elseif($forecast->get_label() == "Rain and Snow")
      {
      echo "Schneeregen";
      }
elseif($forecast->get_label() == "Light Snow")
      {
      echo "Leichter Schneefall";
      }
elseif($forecast->get_label() == "Snow")
      {
      echo "Schneefall";
      }
elseif($forecast->get_label() == "Rain")
      {
      echo "Regen";
      }
elseif($forecast->get_label() == "Foggy")
      {
      echo "neblig";
      }
elseif($forecast->get_label() == "AM Fog/PM Clouds")
      {
      echo "vormittags Nebel/nachmittags bewölkt";
      }
elseif($forecast->get_label() == "Fog Late")
      {
      echo "später neblig";
      }
elseif($forecast->get_label() == "AM Fog/PM Sun")
      {
      echo "vormittags Nebel, nachmittags sonnig";
      }
elseif($forecast->get_label() == "Rain/Snow")
      {
      echo "Regen/Schnee";
      }
elseif($forecast->get_label() == "PM Rain/Snow")
      {
      echo "nachmittags Regen/Schnee";
      }
elseif($forecast->get_label() == "AM Rain/Snow")
      {
      echo "vormittags Regen/Schnee";
      }
elseif($forecast->get_label() == "AM Light Snow")
      {
      echo "vormittags leichter Schneefall";
      }
elseif($forecast->get_label() == "Mostly Clear")
      {
      echo "Überwiegend Klar";
      }
elseif($forecast->get_label() == "Light Rain Early")
      {
      echo "anfangs leichter Regen";
      }
elseif($forecast->get_label() == "PM Snow Showers")
      {
      echo "nachmittags Schneeschauer";
      }
elseif($forecast->get_label() == "Light Rain/Fog")
      {
      echo "Leichter Regen/Nebel";
      }
elseif($forecast->get_label() == "Few Snow Showers")
      {
      echo "vereinzelt Schneeschauer";
      }
elseif($forecast->get_label() == "Showers Late")
      {
      echo "später Schauer";
      }
elseif($forecast->get_label() == "Rain/Snow Showers/Wind")
      {
      echo "Regen/Schneeschauer/Wind";
      }
elseif($forecast->get_label() == "Mostly Sunny")
      {
      echo "Überwiegend sonnig";
      }
elseif($forecast->get_label() == "AM Clouds/PM Sun")
      {
      echo "vormittags Wolken/nachmittags Sonne";
      }
elseif($forecast->get_label() == "AM Snow Showers")
      {
      echo "vormittags Schneeschauer";
      }
elseif($forecast->get_label() == "PM Rain/Snow Showers")
      {
      echo "nachmittags Regen/Schneeschauer";
      }
elseif($forecast->get_label() == "PM Light Snow")
      {
      echo "nachmittags leichter Schneefall";
      }
elseif($forecast->get_label() == "PM Fog")
      {
      echo "nachmittags Nebel";
      }
elseif($forecast->get_label() == "PM Light Rain")
      {
      echo "nachmittags leichter Regen";
      }
elseif($forecast->get_label() == "Showers")
      {
      echo "Schauer";
      }
elseif($forecast->get_label() == "Snow to Rain")
      {
      echo "Schneeregen";
      }
elseif($forecast->get_label() == "Scattered Snow Showers")
      {
      echo "vereinzelte Schneeschauer";
      }
elseif($forecast->get_label() == "Scattered Showers")
      {
      echo "vereinzelte Schauer";
      }
elseif($forecast->get_label() == "AM Showers")
      {
      echo "vormittags Schauer";
      }
elseif($forecast->get_label() == "PM Showers")
      {
      echo "nachmittags Schauer";
      }
elseif($forecast->get_label() == "Scattered T-storms")
      {
      echo "vereinzelte Gewitter";
      }
elseif($forecast->get_label() == "AM Clouds/PM Sun")
      {
      echo "vormittags bewölkt/nach, sonnig";
      }
elseif($forecast->get_label() == "Rain/Snow Showers")
      {
      echo "Regen/Schneeschauer";
      }
elseif($weather->get_condition() == "Few Showers")
      {
      echo "vereinzelte Schauer";
      }
elseif($weather->get_condition() == "Few Snow Showers")
      {
      echo "vereinzelte Schneeschauer";
      }
elseif($forecast->get_label() == "Rain/Snow/Wind")
      {
      echo "Regen/Schnee/Wind";
      }
elseif($forecast->get_label() == "Rain/Wind")
      {
      echo "Regen/Wind";
      }
elseif($forecast->get_label() == "PM Rain")
      {
      echo "nachmittags Regen";
      }
elseif($forecast->get_label() == "Rain Early")
      {
      echo "früh Regen";
      }
elseif($forecast->get_label() == "Clouds Early/Clearing Late")
      {
      echo "früh Wolken/später klar";
      }
elseif($forecast->get_label() == "Rain/Snow Late")
      {
      echo "später Regen/Schnee";
      }
elseif($forecast->get_label() == "AM Snow")
      {
      echo "vormittags Schnee";
      }
elseif($forecast->get_label() == "AM Light Rain")
      {
      echo "vormittags leichter Regen";
      }
elseif($forecast->get_label() == "AM Drizzle")
      {
      echo "vormittags Nieselregen";
      }
elseif($forecast->get_label() == "Drizzle Late")
      {
      echo "später Nieselregen";
      }
elseif($forecast->get_label() == "Rain/Snow Early")
      {
      echo "früh Regen/Schnee";
      }
elseif($forecast->get_label() == "Rain to Snow")
      {
      echo "Regen, in Schnee übergehend";
      }
elseif($forecast->get_label() == "Light Rain Late")
      {
      echo "später leichter Regen";
      }
elseif($forecast->get_label() == "Light Rain/Wind")
      {
      echo "leichter Regen/Wind";
      }
elseif($forecast->get_label() == "Few Showers")
      {
      echo "vereinzelte Schauer";
      }
elseif($forecast->get_label() == "Showers Early")
      {
      echo "früh Schauer";
      }
elseif($forecast->get_label() == "Rain Late")
      {
      echo "später Regen";
      }
elseif($forecast->get_label() == "Isolated T-storms")
      {
      echo "Vereinzelte Gewitter";
      }
elseif($forecast->get_label() == "PM T-storms")
      {
      echo "nachmittags Gewitter";
      }
elseif($forecast->get_label() == "Scattered Snow Showers/Wind")
      {
      echo "vereinzelte Schneeschauer/Wind";
      }
elseif($forecast->get_label() == "PM Snow Showers")
      {
      echo "nachmittags Schneeschauer";
      }
elseif($forecast->get_label() == "PM Showers/Wind")
      {
      echo "nachmittags Schauer/Wind";
      }
elseif($forecast->get_label() == "Showers")
      {
      echo "Schauer";
      }
elseif($forecast->get_label() == "AM Rain")
      {
      echo "vormittags Regen";
      }
elseif($forecast->get_label() == "Partly Cloudy/Wind")
      {
      echo "teilweise bewölkt/Wind";
      }
elseif($forecast->get_label() == "Few Showers/Wind")
      {
      echo "vereinzelte Schauer/Wind";
      }
elseif($forecast->get_label() == "Rain/Thunder")
      {
      echo "Regen/Gewitter";
      }
elseif($forecast->get_label() == "T-showers")
      {
      echo "Gewitterschauer";
      }
elseif($forecast->get_label() == "PM Thundershowers")
      {
      echo "nachmittags Gewitterschauer";
      }
elseif($forecast->get_label() == "Thundershowers Early")
      {
      echo "früh Gewitterschauer";
      }
elseif($forecast->get_label() == "Heavy Rain")
      {
      echo "starker Regen";
      }
elseif($forecast->get_label() == "AM Showers/Wind")
      {
      echo "vormittags Schauer/Wind";
      }
elseif($forecast->get_label() == "Mostly Cloudy/Wind")
      {
      echo "meist bewölkt/Wind";
      }
elseif($forecast->get_label() == "Rain/Snow Showers Late")
      {
      echo "später Regen-/Schneeschnauer";
      }
elseif($forecast->get_label() == "AM Rain/Snow Showers")
      {
      echo "vorm. Regen-/Schneeschauer";
      }
elseif($forecast->get_label() == "Drizzle/Fog")
      {
      echo "Nieselregen/Nebel";
      }
elseif($forecast->get_label() == "Snow Showers Late")
      {
      echo "später Schneeschauer";
      }
elseif($forecast->get_label() == "Cloudy/Wind")
      {
      echo "Bewölkt/Wind";
      }
elseif($forecast->get_label() == "Fog Early/Clouds Late")
      {
      echo "früh Nebel, später Wolken";
      }
elseif($forecast->get_label() == "PM Snow")
      {
      echo "nachm. Schnee";
      }
elseif($forecast->get_label() == "Snow Showers/Wind")
      {
      echo "Schneeschauer/Wind";
      }
elseif($forecast->get_label() == "Light Snow/Wind")
      {
      echo "Leichter Schneefall/Wind";
      }
elseif($forecast->get_label() == "Snow/Wind")
      {
      echo "Schneefall/Wind";
      }
elseif($forecast->get_label() == "Snow Late")
      {
      echo "später Schnee";
      }
elseif($forecast->get_label() == "AM Rain/Snow/Wind")
      {
      echo "vorm. Regen/Schnee/Wind";
      }
elseif($forecast->get_label() == "Showers/Wind")
      {
      echo "Schauer/Wind";
      }
elseif($forecast->get_label() == "Snow Showers Early")
      {
      echo "früh Schneeschauer";
      }
elseif($forecast->get_label() == "Snow Showers Early")
      {
      echo "früh Schneeschauer";
      }
elseif($forecast->get_label() == "Scattered Thunderstorms")
      {
      echo "vereinzelte Gewitter";
      }
elseif($forecast->get_label() == "Heavy Rain/Wind")
      {
      echo "starker Regen/Wind";
      }
elseif($forecast->get_label() == "Thundershowers")
      {
      echo "Gewitterschauer";
      }
elseif($forecast->get_label() == "Isolated Thunderstorms")
      {
      echo "Vereinzelte Gewitter";
      }
elseif($forecast->get_label() == "PM Thunderstorms")
      {
      echo "nachm. Gewitter";
      }
elseif($forecast->get_label() == "Thunderstorms Early")
      {
      echo "früh Gewitter";
      }
elseif($forecast->get_label() == "Light Rain/Wind Early")
      {
      echo "früh leichter Regen/Wind";
      }
elseif($forecast->get_label() == "Thunderstorms")
      {
      echo "Gewitter";
      }
elseif($forecast->get_label() == "AM Light Rain/Wind")
      {
      echo "vorm. leichter Regen/Wind";
      }
elseif($forecast->get_label() == "AM Thundershowers")
      {
      echo "vorm. Gewitterschauer";
      }
elseif($forecast->get_label() == "PM Drizzle")
      {
      echo "nachm. Nieselregen";
      }
elseif($forecast->get_label() == "Drizzle Early")
      {
      echo "früh Nieselregen";
      }
elseif($forecast->get_label() == "AM Ice")
      {
      echo "vorm. Eis";
      }
elseif($forecast->get_label() == "Rain/Snow Showers Early")
      {
      echo "früh Regen-/Schneeschauer";
      }
elseif($forecast->get_label() == "AM Rain/Wind")
      {
      echo "vorm. Regen/Wind";
      }
elseif($forecast->get_label() == "Drizzle/Wind")
      {
      echo "Nieselregen/Wind";
      }
elseif($forecast->get_label() == "AM Drizzle/Wind")
      {
      echo "vorm. Nieselregen/Wind";
      }
elseif($forecast->get_label() == "Snow Grains")
      {
      echo "Schneegriesel";
      }
elseif($forecast->get_label() == "PM Rain/Wind")
      {
      echo "nachm. Regen/Wind";
      }
elseif($forecast->get_label() == "PM Light Rain/Wind")
      {
      echo "nachm. leichter Regen/Wind";
      }
elseif($forecast->get_label() == "Rain/Wind Late")
      {
      echo "später Regen/Wind";
      }
elseif($forecast->get_label() == "PM Light Snow/Wind")
      {
      echo "nachm. leichter Schneefall/Wind";
      }
elseif($forecast->get_label() == "PM Snow Showers/Wind")
      {
      echo "nachm. Schneeschauer/Wind";
      }
elseif($forecast->get_label() == "Heavy Snow")
      {
      echo "Starker Schneefall";
      }
elseif($forecast->get_label() == "Sunny/Wind")
      {
      echo "Sonnig/Wind";
      }
elseif($forecast->get_label() == "Light Rain/Wind Late")
      {
      echo "später leichter Regen/Wind";
      }
elseif($forecast->get_label() == "Scattered Showers/Wind")
      {
      echo "vereinzelte Schauer/Wind";
      }
elseif($forecast->get_label() == "Thunderstorms Early")
      {
      echo "früh Gewitter";
      }
elseif($forecast->get_label() == "Thunderstorms Late")
      {
      echo "später Gewitter";
      }
elseif($forecast->get_label() == "Ice Late")
      {
      echo "später Eis";
      }
elseif($forecast->get_label() == "Heavy Snow")
      {
      echo "Starker Schneefall";
      }
elseif($forecast->get_label() == "Heavy Snow/Wind")
      {
      echo "Starker Schneefall/Wind";
      }
elseif($forecast->get_label() == "Rain/Wind Early")
      {
      echo "früh Regen/Wind";
      }
    else
      {
      echo $forecast->get_label();
      }

  ?>


<br><br>
 
		<?php endforeach; ?>
	
<small>Daten von <a
href="http://weather.yahoo.com">Yahoo!
Weather</a> 
</small><br>
