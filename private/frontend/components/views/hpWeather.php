<?php
/* @var $weatherData array */

use frontend\utilities\WeatherHelper;

if ($weatherData && $weatherData['cod'] == '200') {
	$weatherInfoArray = WeatherHelper::getWeatherInfoFromWeatherCode($weatherData['weather'][0]['id'], $weatherData['dt']);
	$weatherString = Yii::t('front', 'Currently') . ': ';
	$weatherString .= '<i class="fourth-color-text tooltipped wi ' . $weatherInfoArray['icon'] . '" data-position="top" data-delay="50" data-tooltip="' . $weatherInfoArray['description'] . '" style="font-size: 100%"></i> ';
	$weatherString .= round($weatherData['main']['temp']) . '&deg;C';
} else {
	$weatherString = Yii::t( 'front', 'Weather data not available' );
}
echo $weatherString;
