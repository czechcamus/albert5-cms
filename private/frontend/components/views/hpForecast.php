<?php
/* @var $forecastData array */

use frontend\utilities\WeatherHelper;

if ( $forecastData['list'] && $forecastData['cod'] == '200' ) {
	$arrayKey = 0;
	foreach ( $forecastData['list'] as $key => $value ) {
		if ( $key > 1 && strpos( '12:00', substr( $value['dt_txt'], 11, 5 ) ) !== false ) {
			$arrayKey = $key;
			break;
		}
	}

	$weatherData      = $forecastData['list'][ $arrayKey ];
	$weatherInfoArray = WeatherHelper::getWeatherInfoFromWeatherCode( $weatherData['weather'][0]['id'],
		$weatherData['dt'] );
	$weatherString    = ( $arrayKey < 4 ? Yii::t( 'front', 'Today' ) : Yii::t( 'front',
			'Tomorrow' ) ) . ' ' . Yii::t( 'front', 'afternoon' ) . ': ';
	$weatherString .= '<i class="fourth-color-text tooltipped wi ' . $weatherInfoArray['icon'] . '" data-position="top" data-delay="50" data-tooltip="' . $weatherInfoArray['description'] . '" style="font-size: 100%"></i> ';
	$weatherString .= round( $weatherData['main']['temp'] ) . '&deg;C';
} else {
	$weatherString = Yii::t( 'front', 'Weather forecast data not available' );
}
echo $weatherString;
