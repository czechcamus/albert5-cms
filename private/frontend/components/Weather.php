<?php
/**
 * Created by PhpStorm.
 * User: Pavel
 * Date: 7.7.2016
 * Time: 13:11
 */

namespace frontend\components;

use frontend\components\materialize\Widget;
use yii\base\Exception;

/**
 * Class weather displays actual weather info
 * @property $viewName view file name
 * @package frontend\components
 */
class Weather extends Widget {
	/** @var string view file name */
	public $viewName;

	/**
	 * @var array weather information:
	 * coord
	 *      lon, lat
	 * weather
	 *      [id, main, description, icon]
	 * base
	 * main
	 *      temp, pressure, humidity, temp_min, temp_max
	 * visibility
	 * wind
	 *      speed, deg
	 * clouds
	 *      all
	 * dt
	 * sys
	 *      type, id, message, country, sunrise, sunset
	 * id
	 * name
	 * cod         
	 */
	private $_weather = [ ];

	public function init() {
		parent::init();
		$this->setWeather();
	}

	public function run() {
		return $this->render($this->viewName, [
			'weatherData' => $this->_weather
		]);
	}

	private function setWeather() {
		$getFromApi = true;
		$recordFile = \Yii::getAlias( '@frontend' ) . '/runtime/cache/weather.json';
		if ( file_exists( $recordFile ) ) {
			if ( ( time() - filemtime( $recordFile ) ) < 3600 ) {
				$getFromApi = false;
			}
		}
		if ( $getFromApi ) {
			$url = "http://api.openweathermap.org/data/2.5/weather?id=" . \Yii::$app->params['openweathermap']['city_id']
			       . "&APPID=" . \Yii::$app->params['openweathermap']['api_key']
			       . "&units=" . \Yii::$app->params['openweathermap']['units'];
			$contentFromApi = (file_get_contents( $url ) ?: '' );
			$handle         = fopen( $recordFile, "wb" );
			if ( fwrite( $handle, $contentFromApi ) === false ) {
				throw new Exception( \Yii::t( 'front', 'Weather file not writable' ) );
			}
			fclose( $handle );
			clearstatcache();
		}
		if ( file_exists( $recordFile ) ) {
			$handle = fopen($recordFile, "rb");
			$jsonContent = fread($handle, filesize($recordFile));
			$this->_weather = json_decode($jsonContent, true);
			fclose($handle);
		} else {
			throw new Exception(\Yii::t('front', 'Weather file not exists'));
		}
	}
}
