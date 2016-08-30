<?php
/**
 * Created by PhpStorm.
 * User: Pavel
 * Date: 22.8.2016
 * Time: 15:35
 */

namespace frontend\widgets;


use dosamigos\google\maps\Map;
use Yii;

/**
 * Class GoogleMap
 * @inheritdoc
 */
class GoogleMap extends Map
{
	/**
	 * Rewrited method for static map API v2
	 * @param string $maptype
	 * @param string $hl
	 * @param bool $mobile
	 *
	 * @return string
	 */
	public function getStaticMapUrl( $maptype = 'roadmap', $hl = 'en', $mobile = true ) {
		$params = [
			'maptype' => $maptype,
			'mobile' => $mobile,
			'zoom' => $this->zoom,
			'key' => @Yii::$app->params['googleMapsApiKey'] ? : null,
			'center' => $this->center,
			'size' => $this->width . 'x' . $this->height,
			'hl' => $hl,
			'markers' => $this->getMarkersForUrl()
		];

		$params = http_build_query($params);

		return 'https://maps.googleapis.com/maps/api/staticmap?' . $params;
	}
}