<?php
/**
 * Created by PhpStorm.
 * User: Pavel
 * Date: 10.9.2015
 * Time: 9:31
 */

namespace frontend\components;

use yii\base\InvalidConfigException;
use yii\base\Widget;
use yii\helpers\ArrayHelper;

/**
 * Class ArticleList displays article list
 * @property $items is array of articles
 * @property $articlesColumnsCount number of columns
 * @property $displayStyle style of displayed articles
 * @property $wordsCount number of words
 * @property $withImage if list will be displayed with image or not
 * @property $maxImageWidth maximum of image width
 * @property $imageEdgeRatio height / width ratio
 * @package frontend\components
 */
class ArticleList extends Widget
{
	public $items;

	public $articlesColumnsCount = 1;

	public $displayStyle = 'horizontal'; // vertical is another option

	public $wordsCount = 30;

	public $withImage = false;

	public $maxImageWidth;

	public $imageEdgeRatio = 1;


	public function init() {
		parent::init();
		if (!$this->items) {
			throw new InvalidConfigException( \Yii::t( 'front', 'No required parameter given') . ' - items' );
		}
	}

	public function run() {
		$config = [
			'items' => $this->items,
			'articlesColumnsCount' => $this->articlesColumnsCount,
			'wordsCount' => $this->wordsCount,
			'withImage' => $this->withImage
		];
		if ($this->withImage) {
			$config = ArrayHelper::merge($config, [
				'maxImageSize' => [
					'width' => $this->maxImageWidth,
					'height' => $this->getImageHeight()
				]
			]);
		}
		return $this->render('articles' . ucfirst($this->displayStyle), $config);
	}

	protected function getImageHeight() {
		return ceil($this->maxImageWidth * $this->imageEdgeRatio);
	}
}