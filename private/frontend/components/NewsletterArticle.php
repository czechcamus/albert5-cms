<?php
/**
 * Created by PhpStorm.
 * User: Pavel
 * Date: 29.9.2015
 * Time: 7:09
 */

namespace frontend\components;


use common\models\Article;
use frontend\components\materialize\Widget;
use yii\base\InvalidParamException;

/**
 * Class NewsletterArticle displays article perex for newsletter
 * @package frontend\components
 */
class NewsletterArticle extends Widget
{
	public $id;

	public $articleType;

	private $_item;

	public function init() {
		parent::init();
		if ($this->id) {
			$this->_item = Article::findOne($this->id);
		} else {
			throw new InvalidParamException( \Yii::t('front', 'No required parameter given') . ' - id' );
		}
		if (!$this->articleType) $this->articleType = 'normal';
	}

	public function run() {
		return $this->render('newsletterArticle', [
			'item' => $this->_item,
			'articleType' => $this->articleType
		]);
	}
}