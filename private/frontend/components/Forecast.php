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
 * Class Forecast displays weather forecast info
 * @property $viewName view file name
 * @package frontend\components
 */
class Forecast extends Widget {
	/** @var string view file name */
	public $viewName;

	/**
	 * @var array forecast information
	 * city[]
	 * cod
	 * message
	 * cnt
	 * list [
	 * dt
	 * main
	 *      temp, temp_min, temp_max, pressure, sea_level, grnd_level, humidity, temp_kf
	 * weather
	 *     [ id, main, description, icon ]
	 * clouds
	 *     all
	 * wind
	 *     speed, deg
	 * sys
	 *     pod
	 * dt_txt
	 * ]
	 */
	private $_forecast = [ ];

	public function init() {
		parent::init();
		$this->setForecast();
	}

	public function run() {
		return $this->render( $this->viewName, [
			'forecastData' => $this->_forecast
		] );
	}

	private function setForecast() {
		$getFromApi = true;
		$recordFile = \Yii::getAlias( '@frontend' ) . '/runtime/cache/forecast.json';
		if ( file_exists( $recordFile ) ) {
			if ( ( time() - filemtime( $recordFile ) ) < 3600 ) {
				$getFromApi = false;
			}
		}
		if ( $getFromApi ) {
			$url = "http://api.openweathermap.org/data/2.5/forecast?id=" . \Yii::$app->params['openweathermap']['city_id']
			                                        . "&APPID=" . \Yii::$app->params['openweathermap']['api_key']
			                                        . "&units=" . \Yii::$app->params['openweathermap']['units'] . "&cnt=24";
			$contentFromApi = file_get_contents( $url ) ?: '';
			$handle         = fopen( $recordFile, "wb" );
			if ( fwrite( $handle, $contentFromApi ) === false ) {
				throw new Exception( \Yii::t( 'front', 'Forecast file not writable!' ) );
			}
			fclose( $handle );
			clearstatcache();
		}
		if ( file_exists( $recordFile ) ) {
			$handle          = fopen( $recordFile, "rb" );
			$jsonContent     = fread( $handle, filesize( $recordFile ) );
			$this->_forecast = json_decode( $jsonContent, true );
			fclose( $handle );
		} else {
			throw new Exception( \Yii::t( 'front', 'Forecast file not exists' ) );
		}
	}
}