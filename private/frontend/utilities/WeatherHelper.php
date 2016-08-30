<?php
/**
 * Created by PhpStorm.
 * User: Pavel
 * Date: 4.9.2015
 * Time: 21:03
 */

namespace frontend\utilities;


use common\models\LanguageRecord;
use common\models\MenuItemRecord;
use common\models\MenuRecord;
use common\models\WebRecord;
use Yii;
use yii\helpers\Url;

class WeatherHelper {

	/**
	 * Returns weather info array based on weather code and time information
	 * @param $weatherCode
	 * @param $weatherTime
	 *
	 * @return array
	 */
	public static function getWeatherInfoFromWeatherCode( $weatherCode, $weatherTime ) {
		$dayPart = (date('G', $weatherTime) > 6 && date('G', $weatherTime) < 22) ? 'day' : 'night';
		$weatherInfo = [];
		switch ($weatherCode) {
			case '200':
				$weatherInfo['description'] = \Yii::t('front', 'thunderstorm with light rain');
				$weatherInfo['icon'] = "wi-$dayPart-storm-showers";
				break;
			case '201':
				$weatherInfo['description'] = \Yii::t('front', 'thunderstorm with rain');
				$weatherInfo['icon'] = "wi-$dayPart-storm-showers";
				break;
			case '202':
				$weatherInfo['description'] = \Yii::t('front', 'thunderstorm with heavy rain');
				$weatherInfo['icon'] = "wi-$dayPart-thunderstorm";
				break;
			case '210':
				$weatherInfo['description'] = \Yii::t('front', 'light thunderstorm');
				$weatherInfo['icon'] = "wi-$dayPart-storm-showers";
				break;
			case '211':
				$weatherInfo['description'] = \Yii::t('front', 'thunderstorm');
				$weatherInfo['icon'] = "wi-$dayPart-storm-showers";
				break;
			case '212':
				$weatherInfo['description'] = \Yii::t('front', 'heavy thunderstorm');
				$weatherInfo['icon'] = "wi-$dayPart-thunderstorm";
				break;
			case '221':
				$weatherInfo['description'] = \Yii::t('front', 'ragged thunderstorm');
				$weatherInfo['icon'] = "wi-$dayPart-thunderstorm";
				break;
			case '230':
				$weatherInfo['description'] = \Yii::t('front', 'thunderstorm with light drizzle');
				$weatherInfo['icon'] = "wi-$dayPart-storm-showers";
				break;
			case '231':
				$weatherInfo['description'] = \Yii::t('front', 'thunderstorm with drizzle');
				$weatherInfo['icon'] = "wi-$dayPart-storm-showers";
				break;
			case '232':
				$weatherInfo['description'] = \Yii::t('front', 'thunderstorm with heavy drizzle');
				$weatherInfo['icon'] = "wi-$dayPart-storm-showers";
				break;
			case '300':
				$weatherInfo['description'] = \Yii::t('front', 'light intensity drizzle');
				$weatherInfo['icon'] = "wi-showers";
				break;
			case '301':
				$weatherInfo['description'] = \Yii::t('front', 'drizzle');
				$weatherInfo['icon'] = "wi-showers";
				break;
			case '302':
				$weatherInfo['description'] = \Yii::t('front', 'heavy intensity drizzle');
				$weatherInfo['icon'] = "wi-showers";
				break;
			case '310':
				$weatherInfo['description'] = \Yii::t('front', 'light intensity drizzle rain');
				$weatherInfo['icon'] = "wi-showers";
				break;
			case '311':
				$weatherInfo['description'] = \Yii::t('front', 'drizzle rain');
				$weatherInfo['icon'] = "wi-showers";
				break;
			case '312':
				$weatherInfo['description'] = \Yii::t('front', 'heavy intensity drizzle rain');
				$weatherInfo['icon'] = "wi-showers";
				break;
			case '313':
				$weatherInfo['description'] = \Yii::t('front', 'shower rain and drizzle');
				$weatherInfo['icon'] = "wi-showers";
				break;
			case '314':
				$weatherInfo['description'] = \Yii::t('front', 'heavy shower rain and drizzle');
				$weatherInfo['icon'] = "wi-showers";
				break;
			case '321':
				$weatherInfo['description'] = \Yii::t('front', 'shower drizzle');
				$weatherInfo['icon'] = "wi-showers";
				break;
			case '500':
				$weatherInfo['description'] = \Yii::t('front', 'light rain');
				$weatherInfo['icon'] = "wi-rain";
				break;
			case '501':
				$weatherInfo['description'] = \Yii::t('front', 'moderate rain');
				$weatherInfo['icon'] = "wi-rain";
				break;
			case '502':
				$weatherInfo['description'] = \Yii::t('front', 'heavy intensity rain');
				$weatherInfo['icon'] = "wi-rain";
				break;
			case '503':
				$weatherInfo['description'] = \Yii::t('front', 'very heavy rain');
				$weatherInfo['icon'] = "wi-rain";
				break;
			case '504':
				$weatherInfo['description'] = \Yii::t('front', 'extreme rain');
				$weatherInfo['icon'] = "wi-rain";
				break;
			case '511':
				$weatherInfo['description'] = \Yii::t('front', 'freezing rain');
				$weatherInfo['icon'] = "wi-rain-mix";
				break;
			case '520':
				$weatherInfo['description'] = \Yii::t('front', 'light intensity shower rain');
				$weatherInfo['icon'] = "wi-$dayPart-showers";
				break;
			case '521':
				$weatherInfo['description'] = \Yii::t('front', 'shower rain');
				$weatherInfo['icon'] = "wi-$dayPart-showers";
				break;
			case '522':
				$weatherInfo['description'] = \Yii::t('front', 'heavy intensity shower rain');
				$weatherInfo['icon'] = "wi-$dayPart-showers";
				break;
			case '531':
				$weatherInfo['description'] = \Yii::t('front', 'ragged shower rain');
				$weatherInfo['icon'] = "wi-$dayPart-showers";
				break;
			case '600':
				$weatherInfo['description'] = \Yii::t('front', 'light snow');
				$weatherInfo['icon'] = "wi-snow";
				break;
			case '601':
				$weatherInfo['description'] = \Yii::t('front', 'snow');
				$weatherInfo['icon'] = "wi-snow";
				break;
			case '602':
				$weatherInfo['description'] = \Yii::t('front', 'heavy snow');
				$weatherInfo['icon'] = "wi-snow";
				break;
			case '611':
				$weatherInfo['description'] = \Yii::t('front', 'sleet');
				$weatherInfo['icon'] = "wi-sleet";
				break;
			case '612':
				$weatherInfo['description'] = \Yii::t('front', 'shower sleet');
				$weatherInfo['icon'] = "wi-$dayPart-sleet";
				break;
			case '615':
				$weatherInfo['description'] = \Yii::t('front', 'light rain and snow');
				$weatherInfo['icon'] = "wi-sleet";
				break;
			case '616':
				$weatherInfo['description'] = \Yii::t('front', 'rain and snow');
				$weatherInfo['icon'] = "wi-sleet";
				break;
			case '620':
				$weatherInfo['description'] = \Yii::t('front', 'light shower snow');
				$weatherInfo['icon'] = "wi-$dayPart-sleet";
				break;
			case '621':
				$weatherInfo['description'] = \Yii::t('front', 'shower snow');
				$weatherInfo['icon'] = "wi-$dayPart-sleet";
				break;
			case '622':
				$weatherInfo['description'] = \Yii::t('front', 'heavy shower snow');
				$weatherInfo['icon'] = "wi-$dayPart-sleet";
				break;
			case '701':
				$weatherInfo['description'] = \Yii::t('front', 'mist');
				$weatherInfo['icon'] = "wi-fog";
				break;
			case '711':
				$weatherInfo['description'] = \Yii::t('front', 'smoke');
				$weatherInfo['icon'] = "wi-$dayPart-fog";
				break;
			case '721':
				$weatherInfo['description'] = \Yii::t('front', 'smoke');
				$weatherInfo['icon'] = "wi-$dayPart-fog";
				break;
			case '731':
				$weatherInfo['description'] = \Yii::t('front', 'sand, dust whirls');
				$weatherInfo['icon'] = "wi-sandstorm";
				break;
			case '741':
				$weatherInfo['description'] = \Yii::t('front', 'fog');
				$weatherInfo['icon'] = "wi-fog";
				break;
			case '751':
				$weatherInfo['description'] = \Yii::t('front', 'sand');
				$weatherInfo['icon'] = "wi-dust";
				break;
			case '761':
				$weatherInfo['description'] = \Yii::t('front', 'dust');
				$weatherInfo['icon'] = "wi-dust";
				break;
			case '762':
				$weatherInfo['description'] = \Yii::t('front', 'volcanic ash');
				$weatherInfo['icon'] = "wi-dust";
				break;
			case '771':
				$weatherInfo['description'] = \Yii::t('front', 'squalls');
				$weatherInfo['icon'] = "wi-$dayPart-windy";
				break;
			case '781':
				$weatherInfo['description'] = \Yii::t('front', 'tornado');
				$weatherInfo['icon'] = "wi-tornado";
				break;
			case '800':
				$weatherInfo['description'] = \Yii::t('front', 'clear sky');
				$weatherInfo['icon'] = $dayPart == 'day' ? 'wi-day-sunny' : 'wi-night-clear';
				break;
			case '801':
				$weatherInfo['description'] = \Yii::t('front', 'few clouds');
				$weatherInfo['icon'] = "wi-$dayPart-cloudy-high";
				break;
			case '802':
				$weatherInfo['description'] = \Yii::t('front', 'scattered clouds');
				$weatherInfo['icon'] = "wi-$dayPart-cloudy";
				break;
			case '803':
				$weatherInfo['description'] = \Yii::t('front', 'broken clouds');
				$weatherInfo['icon'] = "wi-$dayPart-cloudy";
				break;
			case '804':
				$weatherInfo['description'] = \Yii::t('front', 'overcast clouds');
				$weatherInfo['icon'] = "wi-cloud";
				break;
			case '900':
				$weatherInfo['description'] = \Yii::t('front', 'tornado');
				$weatherInfo['icon'] = "wi-tornado";
				break;
			case '901':
				$weatherInfo['description'] = \Yii::t('front', 'tropical storm');
				$weatherInfo['icon'] = "wi-$dayPart-thunderstorm";
				break;
			case '902':
				$weatherInfo['description'] = \Yii::t('front', 'hurricane');
				$weatherInfo['icon'] = "wi-hurricane";
				break;
			case '903':
				$weatherInfo['description'] = \Yii::t('front', 'cold');
				$weatherInfo['icon'] = "wi-snowflake-cold";
				break;
			case '904':
				$weatherInfo['description'] = \Yii::t('front', 'hot');
				$weatherInfo['icon'] = "wi-hot";
				break;
			case '905':
				$weatherInfo['description'] = \Yii::t('front', 'windy');
				$weatherInfo['icon'] = "wi-strong-wind";
				break;
			case '906':
				$weatherInfo['description'] = \Yii::t('front', 'hail');
				$weatherInfo['icon'] = "wi-hail";
				break;
			case '951':
				$weatherInfo['description'] = \Yii::t('front', 'calm');
				$weatherInfo['icon'] = "wi-wind-beaufort-0";
				break;
			case '952':
				$weatherInfo['description'] = \Yii::t('front', 'light breeze');
				$weatherInfo['icon'] = "wi-wind-beaufort-1";
				break;
			case '953':
				$weatherInfo['description'] = \Yii::t('front', 'gentle breeze');
				$weatherInfo['icon'] = "wi-wind-beaufort-2";
				break;
			case '954':
				$weatherInfo['description'] = \Yii::t('front', 'moderate breeze');
				$weatherInfo['icon'] = "wi-wind-beaufort-3";
				break;
			case '955':
				$weatherInfo['description'] = \Yii::t('front', 'fresh breeze');
				$weatherInfo['icon'] = "wi-wind-beaufort-4";
				break;
			case '956':
				$weatherInfo['description'] = \Yii::t('front', 'strong breeze');
				$weatherInfo['icon'] = "wi-wind-beaufort-5";
				break;
			case '957':
				$weatherInfo['description'] = \Yii::t('front', 'high wind, near gale');
				$weatherInfo['icon'] = "wi-wind-beaufort-6";
				break;
			case '958':
				$weatherInfo['description'] = \Yii::t('front', 'gale');
				$weatherInfo['icon'] = "wi-wind-beaufort-7";
				break;
			case '959':
				$weatherInfo['description'] = \Yii::t('front', 'severe gale');
				$weatherInfo['icon'] = "wi-wind-beaufort-8";
				break;
			case '960':
				$weatherInfo['description'] = \Yii::t('front', 'storm');
				$weatherInfo['icon'] = "wi-wind-beaufort-9";
				break;
			case '961':
				$weatherInfo['description'] = \Yii::t('front', 'violent storm');
				$weatherInfo['icon'] = "wi-wind-beaufort-10";
				break;
			case '966':
				$weatherInfo['description'] = \Yii::t('front', 'hurricane');
				$weatherInfo['icon'] = "wi-hurricane";
				break;
			default:
				$weatherInfo['description'] = \Yii::t('front', 'not available');
				$weatherInfo['icon'] = "wi-na";
				break;
		}
		return $weatherInfo;
	}
}