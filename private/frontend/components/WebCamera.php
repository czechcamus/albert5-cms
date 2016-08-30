<?php
/**
 * Created by PhpStorm.
 * User: Pavel
 * Date: 7.7.2016
 * Time: 13:11
 */

namespace frontend\components;

use frontend\components\materialize\Widget;
use yii\base\ErrorException;

/**
 * Class WebCamera displayes camera image
 * @package frontend\components
 */
class WebCamera extends Widget {

	private $_image = [ ];

	public function init() {
		parent::init();
		$this->setImage();
	}

	public function run() {
		return $this->render( 'webCamera', [
			'image' => $this->_image
		] );
	}

	private function setImage() {
		$ctx = stream_context_create( array(
				'http' => array(
					'timeout' => 60
				)
			)
		);
		try {
			if ( file_get_contents( \Yii::$app->params['webCameraUrl'] . time(), false, $ctx ) ) {
				$this->_image['filename'] = \Yii::$app->params['webCameraUrl'] . time();
				$this->_image['alt']      = \Yii::t( 'front', 'web camera image' );
				$this->_image['tooltip']  = \Yii::t( 'front',
						'web camera image of pool' ) . ' - ' . date( 'j.n.Y H:i:s', time() );
			} else {
				$this->_image['filename'] = \Yii::$app->request->baseUrl . '/basic-assets/img/temp/webkamera.jpg';
				$this->_image['alt']      = \Yii::t( 'front', 'image of pool' );
				$this->_image['tooltip']  = \Yii::t( 'front', 'web camera image not available' );
			}
		} catch ( ErrorException $e ) {
			$this->_image['filename'] = \Yii::$app->request->baseUrl . '/basic-assets/img/temp/webkamera.jpg';
			$this->_image['alt']      = \Yii::t( 'front', 'image of pool' );
			$this->_image['tooltip']  = \Yii::t( 'front', 'web camera image not available' );
		}
		$this->_image['id'] = 'webCameraImg';
	}
}
