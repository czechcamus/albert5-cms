<?php
namespace backend\components;

use common\models\Article;
use yii\base\Widget;

/**
 * Class NewsletterArticle displays perexes of articles in newsletter
 * @package backend\components
 */
class NewsletterArticle extends Widget
{
	public $id;

	public $type;

	private $_article;

	public function init() {
		parent::init();
		$this->_article = Article::findOne($this->id);
	}

	public function run() {
		if ($this->_article) {
			return $this->render('newsletterArticle', [
				'article' => $this->_article,
				'type' => $this->type
			]);
		} else {
			return '';
		}
	}
}