<?php
/* @var $weatherData array */

use frontend\utilities\WeatherHelper;

if ( $weatherData && $weatherData['cod'] == '200' ) {
	$weatherInfoArray = WeatherHelper::getWeatherInfoFromWeatherCode( $weatherData['weather'][0]['id'], $weatherData['dt'] );
	$weatherString    = Yii::t( 'front', 'Weather' ) . ': ';
	$weatherString .= '<strong><i class="tooltipped wi ' . $weatherInfoArray['icon'] . '" data-position="top" data-delay="50" data-tooltip="' . $weatherInfoArray['description'] . '"></i> ';
	$weatherString .= round( $weatherData['main']['temp'] ) . '&deg;C</strong> <span>&#9724;</span>';
} else {
	$weatherString = Yii::t( 'front', 'Weather data not available' );
}
echo $weatherString;
