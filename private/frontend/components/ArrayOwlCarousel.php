<?php
/**
 * Created by PhpStorm.
 * User: Pavel
 * Date: 8.9.2015
 * Time: 7:57
 */

namespace frontend\components;


use yii\base\InvalidConfigException;
use yii\base\Widget;

/**
 * Class OwlCarousel displays images with links in jQuery owl carousel
 * @property $sourceImagesContentId integer is Id of page with images and links - required
 * @package frontend\components
 */
class ArrayOwlCarousel extends Widget
{
	/** @var integer $sourceImagesContentId */
	public $sourceImagesContentId;

	public $items;

	public function init() {
		parent::init();
		if (!$this->items) {
			throw new InvalidConfigException( \Yii::t('front', 'No required parameter given') . ' - items' );
		}
	}

	public function run() {
		return $this->render('arrayOwlCarousel', [
			'items' => $this->items
		]);
	}
}