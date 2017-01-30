<?php
/**
 * Created by PhpStorm.
 * User: Pavel
 * Date: 11.9.2015
 * Time: 22:42
 */

namespace frontend\components;


use common\models\ContentRecord;
use frontend\utilities\FrontEndHelper;
use Yii;
use yii\bootstrap\Widget;

/**
 * Class RecentArticlesList displays recent articles list
 * @property integer $recentArticlesCount number of recent articles to display
 * @property string $viewName name of view file
 * @package frontend\components
 */
class RecentArticlesList extends Widget
{
	/** @var integer number of recent articles to display */
	public $recentArticlesCount = 5;

	/** @var string $viewName name of view file */
	public $viewName = 'simpleTitleList';

	private $_items;

	public function init() {
		parent::init();
		$language_id = FrontEndHelper::getLanguageIdFromAcronym();
		$web_id = FrontEndHelper::getWebIdFromTextId(\Yii::$app->request->get('web', \Yii::$app->params['defaultWeb']));
		$query = ContentRecord::find()
          ->distinct()
          ->where(['content.content_type' => ContentRecord::TYPE_ARTICLE])
          ->andWhere(['content.active' => true]);
		if (\Yii::$app->user->isGuest) {
			$query->andWhere(['content.public' => true]);
		}
		$query->andWhere(['content.language_id' => $language_id])
	      ->innerJoin('article_category', 'article_category.article_id=content.id')
	      ->innerJoin('category', 'category.id=article_category.category_id')
	      ->innerJoin('menu_item_content', 'menu_item_content.category_id=category.id')
	      ->innerJoin('menu_item', 'menu_item.id=menu_item_content.menu_item_id')
	      ->innerJoin('menu', 'menu.id=menu_item.menu_id')
	      ->andWhere(['menu.web_id' => $web_id])
	      ->orderBy(['created_at' => SORT_DESC])
	      ->limit($this->recentArticlesCount);
		$this->_items = $query->all();
	}

	public function run() {
		return $this->render($this->viewName, [
			'items' => $this->_items,
			'menuUrlParts' => [],
			'title' => Yii::t('front', 'Recent articles')
		]);
	}
}