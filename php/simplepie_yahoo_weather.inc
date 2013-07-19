<?php
/**
 * SimplePie Add-on for Yahoo! Weather
 *
 * Copyright (c) 2004-2007, Ryan Parman and Geoffrey Sneddon
 * All rights reserved. License matches the current SimplePie license.
 */

// Define the namespace for simplicity.
if (!defined('SIMPLEPIE_NAMESPACE_YWEATHER')) {
    define('SIMPLEPIE_NAMESPACE_YWEATHER', 'http://xml.weather.yahoo.com/ns/rss/1.0');
}

// Extend the SimplePie_Item class.
class SimplePie_Item_YWeather extends SimplePie_Item
{
    // Get <yweather:location> data
    function _get_location()
    {
        $feed = $this->get_feed();
        return $feed->get_channel_tags(SIMPLEPIE_NAMESPACE_YWEATHER, 'location');
    }

    function get_city()
    {
        $data = $this->_get_location();
        return $data[0]['attribs']['']['city'];
    }

    function get_region()
    {
        $data = $this->_get_location();
        return $data[0]['attribs']['']['region'];
    }

    function get_state()
    {
        return $this->get_region();
    }

    function get_country()
    {
        $data = $this->_get_location();
        return $data[0]['attribs']['']['country'];
    }


    // Get <yweather:units> data
    function _get_units()
    {
        $feed = $this->get_feed();
        return $feed->get_channel_tags(SIMPLEPIE_NAMESPACE_YWEATHER, 'units');
    }

    function get_units_temp()
    {
        $data = $this->_get_units();
        return $data[0]['attribs']['']['temperature'];
    }

    function get_units_distance()
    {
        $data = $this->_get_units();
        return $data[0]['attribs']['']['distance'];
    }

    function get_units_pressure()
    {
        $data = $this->_get_units();
        return $data[0]['attribs']['']['pressure'];
    }

    function get_units_speed()
    {
        $data = $this->_get_units();
        return $data[0]['attribs']['']['speed'];
    }


    // Get <yweather:wind> data
    function _get_wind()
    {
        $feed = $this->get_feed();
        return $feed->get_channel_tags(SIMPLEPIE_NAMESPACE_YWEATHER, 'wind');
    }

    function get_wind_chill()
    {
        $data = $this->_get_wind();
        return $data[0]['attribs']['']['chill'];
    }

    function get_wind_direction_degrees()
    {
        $data = $this->_get_wind();
        return $data[0]['attribs']['']['direction'];
    }

    function get_wind_direction()
    {
        $wind_direction = $this->get_wind_direction_degrees();

        // Calculations taken from http://jivebay.com/2007/07/25/get-yahoo-weather-with-simplepie/
        if ($wind_direction > 348.75 || $wind_direction < 11.25)
            $wind_direction_converted = "N";
        else if ($wind_direction > 11.25 || $wind_direction < 33.75)
            $wind_direction_converted = "NNE";
        else if ($wind_direction > 33.75 || $wind_direction < 56.25)
            $wind_direction_converted = "NE";
        else if ($wind_direction > 56.25 || $wind_direction < 78.75)
            $wind_direction_converted = "ENE";
        else if ($wind_direction > 78.75 || $wind_direction < 101.25)
            $wind_direction_converted = "E";
        else if ($wind_direction > 101.25 || $wind_direction < 123.75)
            $wind_direction_converted = "ESE";
        else if ($wind_direction > 123.75 || $wind_direction < 146.25)
            $wind_direction_converted = "SE";
        else if ($wind_direction > 146.25 || $wind_direction < 168.75)
            $wind_direction_converted = "SSE";
        else if ($wind_direction > 168.75 || $wind_direction < 191.25)
            $wind_direction_converted = "S";
        else if ($wind_direction > 191.25 || $wind_direction < 213.75)
            $wind_direction_converted = "SSW";
        else if ($wind_direction > 213.75 || $wind_direction < 236.25)
            $wind_direction_converted = "SW";
        else if ($wind_direction > 236.25 || $wind_direction < 258.75)
            $wind_direction_converted = "WSW";
        else if ($wind_direction > 258.75 || $wind_direction < 281.25)
            $wind_direction_converted = "W";
        else if ($wind_direction > 281.25 || $wind_direction < 303.75)
            $wind_direction_converted = "WNW";
        else if ($wind_direction > 303.75 || $wind_direction < 326.25)
            $wind_direction_converted = "NW";
        else if ($wind_direction > 326.25 || $wind_direction < 348.75)
            $wind_direction_converted = "NNW";
        else $wind_direction_converted = null;

        return $wind_direction_converted;
    }

    function get_wind_speed()
    {
        $data = $this->_get_wind();
        return $data[0]['attribs']['']['speed'];
    }


    // Get <yweather:atmosphere> data
    function _get_atmosphere()
    {
        $feed = $this->get_feed();
        return $feed->get_channel_tags(SIMPLEPIE_NAMESPACE_YWEATHER, 'atmosphere');
    }

    function get_humidity()
    {
        $data = $this->_get_atmosphere();
        return $data[0]['attribs']['']['humidity'];
    }

    function get_visibility()
    {
        $data = $this->_get_atmosphere();
        return $data[0]['attribs']['']['visibility'];
    }

    function get_pressure()
    {
        $data = $this->_get_atmosphere();
        return $data[0]['attribs']['']['pressure'];
    }

    function get_rising()
    {
        $data = $this->_get_atmosphere();
        return $data[0]['attribs']['']['rising'];
    }


    // Get <yweather:astronomy> data
    function _get_astronomy()
    {
        $feed = $this->get_feed();
        return $feed->get_channel_tags(SIMPLEPIE_NAMESPACE_YWEATHER, 'astronomy');
    }

    function get_sunrise()
    {
        $data = $this->_get_astronomy();
        return $data[0]['attribs']['']['sunrise'];
    }

    function get_sunset()
    {
        $data = $this->_get_astronomy();
        return $data[0]['attribs']['']['sunset'];
    }


    // Get <yweather:condition> data
    function _get_condition()
    {
        return $this->get_item_tags(SIMPLEPIE_NAMESPACE_YWEATHER, 'condition');
    }

    function get_condition()
    {
        $data = $this->_get_condition();
        return $data[0]['attribs']['']['text'];
    }

    function get_condition_code()
    {
        $data = $this->_get_condition();
        return $data[0]['attribs']['']['code'];
    }

    function get_condition_image()
    {
        return 'http://l.yimg.com/us.yimg.com/i/us/we/52/' . $this->get_condition_code() . '.gif';
    }

    function get_temperature()
    {
        $data = $this->_get_condition();
        return $data[0]['attribs']['']['temp'];
    }

    function get_last_updated($format = null)
    {
        $data = $this->_get_condition();

        if ($format) {
            return date($format, SimplePie_Misc::parse_date($data[0]['attribs']['']['date']));
        }
        else
        {
            return $data[0]['attribs']['']['date'];
        }
    }


    // Get <yweather:forecast> data
    function _get_forecast()
    {
        return $this->get_item_tags(SIMPLEPIE_NAMESPACE_YWEATHER, 'forecast');
    }

    function get_forecast($key = 0)
    {
        $forecasts = $this->get_forecasts();
        if (isset($forecasts[$key])) {
            return $forecasts[$key];
        }
        else
        {
            return null;
        }
    }

    function get_forecasts()
    {
        $temp = array();
        $data = $this->_get_forecast();
        foreach ($data as $forecast)
        {
            $temp[] = new SimplePie_YWeather_Forecast($forecast['attribs']['']['date'], $forecast['attribs']['']['low'], $forecast['attribs']['']['high'], $forecast['attribs']['']['text'], $forecast['attribs']['']['code']);
        }
        return $temp;
    }
}

class SimplePie_YWeather_Forecast
{
    var $date;
    var $low;
    var $high;
    var $label;
    var $code;

    function SimplePie_YWeather_Forecast($date, $low, $high, $label, $code)
    {
        $this->date = $date;
        $this->low = $low;
        $this->high = $high;
        $this->label = $label;
        $this->code = $code;
    }

    function get_date($format = null)
    {
        if ($format) {
            return date($format, SimplePie_Misc::parse_date($this->date));
        }
        else
        {
            return $this->date;
        }
    }

    function get_low()
    {
        return $this->low;
    }

    function get_high()
    {
        return $this->high;
    }

    function get_label()
    {
        return $this->label;
    }

    function get_code()
    {
        return $this->code;
    }

    function get_image()
    {
        return 'http://l.yimg.com/us.yimg.com/i/us/we/52/' . $this->get_code() . '.gif';
    }
}

?>