<?php
/**
 * Created by PhpStorm.
 * User: Pavel
 * Date: 30.9.2015
 * Time: 15:22
 */

namespace frontend\models;


use common\models\Article;
use common\models\ContentRecord;
use frontend\utilities\FrontEndHelper;
use Yii;
use yii\db\Query;
use yii\helpers\Inflector;

/**
 * Class ArticleContent extends Article for frontend purposes
 * @package frontend\models
 */
class ArticleContent extends Article
{
	/**
	 * Gets article data for search functionality
	 * @param $q string search query
	 * @return array
	 */
	public function search($q) {
		$q = htmlentities($q);
		$idw = FrontEndHelper::getWebIdFromTextId(\Yii::$app->request->get('web', \Yii::$app->params['defaultWeb']));
		$idl = FrontEndHelper::getLanguageIdFromAcronym();
		$query = (new Query())->select(['content.id AS article_id', 'content.title AS article_title', 'perex', 'content.updated_at AS article_updated_at', 'menu_item.id AS menu_item_id', 'menu_item.title AS menu_item_title'])
			->distinct()
			->from('content')
			->innerJoin('article_category', 'article_category.article_id=content.id')
			->innerJoin('category', 'category.id=article_category.category_id')
			->innerJoin('menu_item_content', 'menu_item_content.category_id=category.id')
			->innerJoin('menu_item', 'menu_item.id=menu_item_content.menu_item_id')
			->innerJoin('menu', 'menu.id=menu_item.menu_id')
			->where(['menu_item.language_id' => $idl])
			->andWhere(['content.active' => true]);
			if (\Yii::$app->user->isGuest) {
				$query->andWhere(['content.public' => true])
					->andWhere(['menu_item.public' => true]);
			}
		$query->andWhere(['menu.web_id' => $idw])
			->andWhere(['menu_item.active' => true])
			->andWhere(['or', ['like', 'content.title', $q], ['like', 'content.perex', $q], ['like', 'content.description', $q]])
			->orderBy(['content.updated_at' => SORT_DESC]);

		return $query->all();
	}

	/**
	 * Gets article data for tag functionality
	 * @param $tag string search query
	 * @return array
	 */
	public function searchTags($tag) {
		$idw = FrontEndHelper::getWebIdFromTextId(\Yii::$app->request->get('web', \Yii::$app->params['defaultWeb']));
		$idl = FrontEndHelper::getLanguageIdFromAcronym();
		$sql = "( SELECT DISTINCT content.id AS article_id, content.title AS article_title, perex, content.updated_at AS article_updated_at, menu_item.id AS menu_item_id, menu_item.title AS menu_item_title
			FROM `content`
			INNER JOIN `article_category` ON article_category.article_id=content.id
			INNER JOIN `category` ON category.id=article_category.category_id
			INNER JOIN `menu_item_content` ON menu_item_content.category_id=category.id
			INNER JOIN `menu_item` ON menu_item.id=menu_item_content.menu_item_id
			INNER JOIN `menu` ON menu.id=menu_item.menu_id
			INNER JOIN `content_tag` ON content_tag.content_id=content.id
			INNER JOIN `tag` ON tag.id=content_tag.tag_id
			WHERE ((((((`content`.`content_type`=:content_type))
				AND (`content`.`active`=TRUE)) " . (Yii::$app->user->isGuest ? " AND (`content`.`public`=TRUE))" : '') . "
				AND (`content`.`language_id`=:language_id))
				AND (`menu`.`web_id`=:web_id))
				AND (`tag`.`name`=:tag))
			ORDER BY `content`.`updated_at`";

		$query = ContentRecord::findBySql($sql, [
			':content_type' => ContentRecord::TYPE_ARTICLE,
			':language_id' => $idl,
			':web_id' => $idw,
			':tag' => $tag
		]);

		return $query->asArray()->all();
	}

	/**
	 * Gets article url
	 * @param $menuTitle
	 * @param $menuId
	 * @param $articleTitle
	 * @param $articleId
	 *
	 * @return array
	 */
	public static function getUrl( $menuTitle, $menuId, $articleTitle, $articleId ) {
		$url =  [
			'page/article',
			'name' => Inflector::slug(strip_tags($menuTitle)),
			'id' => $menuId,
			'article' => Inflector::slug(strip_tags($articleTitle)),
			'ida' => $articleId,
			'web' => \Yii::$app->request->get('web'),
			'language' => \Yii::$app->request->get('language')
		];
		return $url;
	}

	/**
	 * Gets menu url parts for given article
	 * @param $articleId
	 * @return array|bool
	 */
	public static function getMenuUrlParts( $articleId ) {
		$sql = "SELECT menu_item.id,menu_item.title AS name FROM content, article_category, category, menu_item_content, menu_item WHERE content.id=:id AND content.id=article_category.article_id AND article_category.category_id=category.id AND category.id=menu_item_content.category_id AND menu_item_content.menu_item_id=menu_item.id";
		$query = \Yii::$app->db->createCommand($sql);
		$query->bindValue(':id', $articleId);
		$urlParts = $query->queryOne();
		if (isset($urlParts['name'])) {
			$urlParts['name'] = Inflector::slug(strip_tags($urlParts['name']));
		}
		return $urlParts;
	}
}