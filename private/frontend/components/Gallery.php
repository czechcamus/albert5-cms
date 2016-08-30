<?php
/**
 * Created by PhpStorm.
 * User: Pavel
 * Date: 9.9.2015
 * Time: 10:46
 */

namespace frontend\components;


use common\models\Image;
use kartik\widgets\Widget;
use pavlinter\display\DisplayImage;
use yii\base\InvalidConfigException;
use yii\helpers\ArrayHelper;

/**
 * Class Gallery displays gallery
 * @package frontend\components
 */
class Gallery extends Widget {
	public $id;

	public $type;

	public $align;

	/** @var \common\models\Gallery $_gallery */
	private $_gallery;

	public function init() {
		parent::init();
		if ( $this->id ) {
			$this->_gallery = \common\models\Gallery::findOne( $this->id );
		} else {
			throw new InvalidConfigException( \Yii::t( 'front', 'No required parameter given' ) . ' - id' );
		}
		if ( ! $this->type ) {
			throw new InvalidConfigException( \Yii::t( 'front', 'No required parameter given' ) . ' - type' );
		}
	}

	public function run() {
		$config = [
			'gallery' => $this->_gallery
		];
		if ( $this->_gallery->images ) {
			if ( $this->type == 'box' ) {
				$config = ArrayHelper::merge( $config, [
					'image' => $this->getRandomImage(),
					'align' => $this->align
				] );
			} elseif ( $this->type == 'bar' ) {
				$config = ArrayHelper::merge( $config, [
					'images' => $this->getImages()
				] );
			}

			return $this->render( $this->type . 'Gallery', $config );
		} else {
			return '';
		}

	}

	protected function getRandomImage() {
		$image = '';
		if ( $this->_gallery->images ) {
			$imageId = array_rand( $this->_gallery->images, 1 );
			/** @var Image $imageModel */
			$imageModel = $this->_gallery->images[ $imageId ];
			if ( $imageModel ) {
				$image = DisplayImage::widget( [
					'width'    => \Yii::$app->params['galleryLinkImageSize'],
					'height'   => \Yii::$app->params['galleryLinkImageSize'],
					'options'  => [
						'class' => 'responsive-img hoverable',
						'title' => $imageModel->title
					],
					'category' => 'all',
					'image'    => $imageModel->filename
				] );
			}
		}

		return $image;
	}

	protected function getImages() {
		$images = [ ];
		$imagesCount = count($this->_gallery->images);
		$limit = ($imagesCount >= 4 ? 4 : $imagesCount) - 1;
		for ( $i = 0; $i <= $limit; ++ $i ) {
			$imageModel = $this->_gallery->images[ $i ];
			$images[]   = DisplayImage::widget( [
				'width'    => \Yii::$app->params['galleryLinkImageSize'],
				'height'   => \Yii::$app->params['galleryLinkImageSize'],
				'options'  => [
					'class' => 'responsive-img',
					'title' => $imageModel->title
				],
				'category' => 'all',
				'image'    => $imageModel->filename
			] );
		}

		return $images;
	}
}