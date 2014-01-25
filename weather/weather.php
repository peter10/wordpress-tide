<?php

/*
  Plugin Name: Weather
  Description: get and display current weather
  Author: Peter
  Version: 1
  Author URI: superawesome.biz
 */


/* Add our function to the widgets_init hook. */
add_action('widgets_init', array('Tide_Weather_Widget', 'loadWidget'));

class Tide_Weather_Widget extends WP_Widget {

    static function loadWidget() {
        register_widget('Tide_Weather_Widget');
    }

    function Tide_Weather_Widget() {
        //wp_enqueue_style( 'tide-weather', plugins_url( 'style.css' , __FILE__ ) );
        wp_enqueue_style('tide-weather', get_stylesheet_directory_uri() . '/weather/style.css');

        $widget_ops = array('classname' => 'widget_tide_weather', 'description' => 'weather widget for TIDE');
        $this->WP_Widget('tide_weather', 'TIDE weather', $widget_ops);
    }

    // see if weather forecast is current; load if not
    function loadFeed() {
        $filename = dirname(__FILE__) . '/forecast.xml';
        if (!file_exists($filename) || 10 < (time() - filemtime($filename)) / ( 60 * 10 )) {
            $forecast = simplexml_load_file('http://weather.yahooapis.com/forecastrss?w=4118&u=c');
            $forecast->asXML($filename);
        } else {
            $forecast = simplexml_load_file($filename);
        }
        return $forecast;
    }

    function widget($args, $instance) {

        extract($args, EXTR_SKIP);

        $title = empty($instance['title']) ? ' ' : apply_filters('widget_title', $instance['title']);

        $weather = $this->loadFeed();

        // fail silently if the feed didn't load
        if (!method_exists($weather, 'xpath')) {
            return;
        }

        $weather->registerXPathNamespace('yweather', 'http://xml.weather.yahoo.com/ns/rss/1.0');

        foreach ($weather->channel->item as $item) {
            $condition = $item->xpath('yweather:condition');
        }
        $text = (string) $condition[0]['text'];
        $code = (int) $condition[0]['code'];
        $temp = (int) $condition[0]['temp'];

        foreach ($weather->channel as $channel) {
            $wind = $channel->xpath('yweather:wind');
        }
        $speed = (int) $wind[0]['speed'];
        $direction = (int) $wind[0]['direction'];

        if ($direction < 11.25) {
            $compass = 'N';
        } elseif ($direction < 33.75) {
            $compass = 'NNE';
        } elseif ($direction < 56.25) {
            $compass = 'NE';
        } elseif ($direction < 78.75) {
            $compass = 'ENE';
        } elseif ($direction < 101.25) {
            $compass = 'E';
        } elseif ($direction < 123.75) {
            $compass = 'ESE';
        } elseif ($direction < 146.25) {
            $compass = 'SE';
        } elseif ($direction < 168.75) {
            $compass = 'SSE';
        } elseif ($direction < 191.25) {
            $compass = 'S';
        } elseif ($direction < 213.75) {
            $compass = 'SSW';
        } elseif ($direction < 236.25) {
            $compass = 'SW';
        } elseif ($direction < 258.75) {
            $compass = 'WSW';
        } elseif ($direction < 281.25) {
            $compass = 'W';
        } elseif ($direction < 303.75) {
            $compass = 'WNW';
        } elseif ($direction < 326.25) {
            $compass = 'NW';
        } elseif ($direction < 348.75) {
            $compass = 'NNW';
        } else {
            $compass = 'N';
        }
        $wind = 'wind ' . $speed . ' km/hr' . ' ' . $compass;

        // css class for image sprite. Codes from http://developer.yahoo.com/weather/
        switch ($code) {
//            case 0:
//            case 1:
//            case 2:
//                // tropical storms
//                break;
            case 3:
            case 4:
                // thunder storms 3 10
                $class_name = 'thunderstorm';
                break;
            case 5:
            case 6:
            case 7:
                //snow + rain 4 6 
                $class_name = 'mixed_precipitation';
                break;
            case 8:
            case 9:
            case 10:
            case 11:
            case 12:
                // showers 2 4
                $class_name = 'showers';
                break;
            case 13:
            case 14:
            case 15:
            case 16:
                // snow/light snow 3 8
                $class_name = 'snow';
                break;
            case 17:
            case 18:
                // hail sleet 1 4
                $class_name = 'hail';
                break;
            case 19:
            case 20:
            case 21:
            case 22:
                // hazy 3 3, 4 3
                $class_name = 'hazy';
                break;
//            case 23:
//            case 24:
//                // windy
//            case 25:
//                // cold
            case 26:
                // cloudy 1 3
                $class_name = 'cloudy';
                break;
            case 27:
            case 28:
                //mostly cloudy 3 2, 4 2
                $class_name = 'mostly_cloudy';
                break;
            case 29:
            case 30:
                //partly cloudy 3 1, 4 1
                $class_name = 'partly_cloudy';
                break;
            case 31:
            case 32:
            case 33:
            case 34:
                //clear 1 9, 2 9
                $class_name = 'clear';
                break;
            case 35:
                //mixed rain and hail 4 6 
                $class_name = 'mixed_precipitation';
                break;
//            case 36:
//                //hot
            case 37:
            case 38:
            case 39:
                //scattered thunderstorms 3 9, 4 9
                $class_name = 'scattered_thunderstorms';
                break;
            case 40:
                //scattered showers 1 6, 2 6
                $class_name = 'scattered_showers';
                break;
            case 41:
                //heavy snow 8 4
                $class_name = 'heavy_snow';
                break;
            case 42:
                //scattered snow showers 3 7, 4 7
                $class_name = 'scattered_snow';
                break;
            case 43:
                //heavy snow 8 4
                $class_name = 'heavy_snow';
                break;
            case 44:
                //partly cloudy 1 2, 2 2
                $class_name = 'partly_cloudy';
                break;
            case 45:
                //thundershowers  3 9, 4 9
                $class_name = 'scattered_thunderstorms';
                break;
            case 46:
                //snow showers 3 8
                $class_name = 'snow';
                break;
            case 47:
                //isolated thundershowers 3 9, 4 9
                $class_name = 'scattered_thunderstorms';
                break;
            default:
                // question mark 2 3
                $class_name = 'question';
        }
        // add to class name if it's nighttime
        if (time() < date_sunrise(time(), SUNFUNCS_RET_TIMESTAMP, 43.624256, -79.361157) || time() > date_sunset(time(), SUNFUNCS_RET_TIMESTAMP, 43.624256, -79.361157)) {
            $class_name .= ' night';
        }

        echo $before_widget;
        echo "\n<div class='tide-weather $class_name'>\n";
        echo "\t<div><span>$temp&deg;</span><br />$text<br />$wind</div>\n";
        echo "</div>\n";
        echo $after_widget;
    }

}
