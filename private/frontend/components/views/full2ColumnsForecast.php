<?php
/* @var $forecastData array */

use frontend\utilities\WeatherHelper;

if ( $forecastData['list'] && $forecastData['cod'] == '200' ) {
	$currentDay    = $oldDay = '';
	$weatherString = '<div class="row forecast">';
	foreach ( $forecastData['list'] as $key => $value ) {
		$currentDay = date( 'd.m.Y', $value['dt'] );
		if ( $currentDay != $oldDay ) {
			if ( $key > 0 ) {
				$weatherString .= '</tbody></table></div>';
			}
			$weatherString .= '<div class="col s12 l6"><table class="striped responsive-table">';
			$weatherString .= '<thead><tr><th>' . Yii::t( 'front', 'time' ) . '</th>';
			$weatherString .= '<th>' . Yii::t( 'front', 'weather' ) . '</th>';
			$weatherString .= '<th>' . Yii::t( 'front', 'wind' ) . '</th></tr></thead><tbody>';
			$oldDay = $currentDay;
		}
		$weatherString .= '<tr>';
		$weatherString .= '<td><span>' . date( 'd.m.Y', $value['dt'] ) . '</span><br><strong>' . date( 'H:i',
				$value['dt'] ) . '</strong></td>';
		$weatherInfoArray = WeatherHelper::getWeatherInfoFromWeatherCode( $value['weather'][0]['id'], $value['dt'] );
		$weatherString .= '<td><i class="tooltipped wi ' . $weatherInfoArray['icon'] . ' fourth-color-text" data-position="top" data-delay="50" data-tooltip="' . $weatherInfoArray['description'] . '"></i> ';
		$weatherString .= round( $value['main']['temp'] ) . '&deg;C</td>';
		$weatherString .= '<td><i class="wi wi-wind from-' . round( $value['wind']['deg'] ) . '-deg"></i> ' . round( $value['wind']['speed'],
				1 ) . ' m/s</td>';
		$weatherString .= '</tr>';
	}
	$weatherString .= '</table></div></div>';
} else {
	$weatherString = Yii::t( 'front', 'Weather forecast data not available' );
}
echo $weatherString;
