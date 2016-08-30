<?php
/**
 * Created by PhpStorm.
 * User: Pavel
 * Date: 19.8.2015
 * Time: 12:50
 */

namespace frontend\models;


use common\models\ContentRecord;
use common\models\MenuItemRecord;
use common\models\MenuRecord;
use frontend\utilities\FrontEndHelper;
use Yii;
use yii\helpers\Inflector;

/**
 * Class MenuContent extends MenuItemRecord for frontend purposes
 * @package frontend\models
 */
class MenuContent extends MenuItemRecord
{
	/**
	 * Gets category data for search functionality
	 * @param $q string search query
	 * @return MenuContent[]
	 */
	public function searchCategory($q) {
		$q = htmlentities($q);
		$idw = FrontEndHelper::getWebIdFromTextId(\Yii::$app->request->get('web', \Yii::$app->params['defaultWeb']));
		$idl = FrontEndHelper::getLanguageIdFromAcronym();
		$query = self::find()
			->innerJoin('menu_item_content', 'menu_item_content.menu_item_id=menu_item.id')
			->innerJoin('category', 'category.id=menu_item_content.category_id')
			->innerJoin('menu', 'menu.id=menu_item.menu_id')
			->where(['menu.web_id' => $idw])
			->andWhere(['menu_item.language_id' => $idl])
			->andWhere(['menu_item.active' => true])
			->andWhere(['or', ['like', 'menu_item.title', $q], ['like', 'category.description', $q]])
			->orderBy(['category.updated_at' => SORT_DESC])
			->all();

		return $query;
	}

	/**
	 * Gets content data for search functionality
	 * @param $q string search query
	 * @return MenuContent[]
	 */
	public function searchContent($q) {
		$q = htmlentities($q);
		$idw = FrontEndHelper::getWebIdFromTextId(\Yii::$app->request->get('web', \Yii::$app->params['defaultWeb']));
		$idl = FrontEndHelper::getLanguageIdFromAcronym();
		$query = self::find()
             ->innerJoin('menu_item_content', 'menu_item_content.menu_item_id=menu_item.id')
             ->innerJoin('content', 'content.id=menu_item_content.content_id')
             ->innerJoin('menu', 'menu.id=menu_item.menu_id')
             ->where(['menu.web_id' => $idw])
             ->andWhere(['menu_item.language_id' => $idl])
             ->andWhere(['menu_item.active' => true])
             ->andWhere(['or', ['like', 'menu_item.title', $q], ['like', 'content.perex', $q], ['like', 'content.description', $q]])
             ->orderBy(['content.updated_at' => SORT_DESC])
             ->all();

		return $query;
	}

	/**
	 * Gets content data for tag functionality
	 * @param $tag string search query
	 * @return MenuContent[]
	 */
	public function searchTags($tag) {
		$idw = FrontEndHelper::getWebIdFromTextId(\Yii::$app->request->get('web', \Yii::$app->params['defaultWeb']));
		$idl = FrontEndHelper::getLanguageIdFromAcronym();
		$query = self::find()
			->innerJoin('menu_item_content', 'menu_item_content.menu_item_id=menu_item.id')
			->innerJoin('content', 'content.id=menu_item_content.content_id')
			->innerJoin('menu', 'menu.id=menu_item.menu_id')
			->innerJoin('content_tag', 'content_tag.content_id=content.id')
			->innerJoin('tag', 'tag.id=content_tag.tag_id')
			->where(['menu.web_id' => $idw])
			->andWhere(['menu_item.language_id' => $idl])
			->andWhere(['menu_item.active' => true])
			->andWhere(['tag.name' => $tag])
			->orderBy(['content.updated_at' => SORT_DESC])
			->all();

		return $query;
	}

	/**
	 * Gets url actual item
	 * @return array|string
	 */
	public function getUrl() {
		$url =  $this->content_type == MenuItemRecord::CONTENT_LINK ? $this->link_url . ',' . $this->id : ['page/menu', 'web' => \Yii::$app->request->get('web'), 'language' => \Yii::$app->request->get('language'), 'name' => Inflector::slug(strip_tags($this->title)), 'id' => $this->id];
		return $url;
	}

	/**
	 * Gets url array for back link
	 * @return array
	 */
	public function getBackUrl() {
		$url =  ['page/menu', 'web' => \Yii::$app->request->get('web'), 'language' => \Yii::$app->request->get('language'), 'name' => Inflector::slug(strip_tags($this->parentItem->title)), 'id' => $this->parent_id];
		return $url;
	}

	/**
	 * Creates menu items tree
	 *
	 * @param $language_id
	 * @param null|int $menu_id
	 * @param null|int $parent_id
	 * @param array $itemsTree
	 * @param boolean $withDropdowns
	 *
	 * @return array
	 */
	public static function getItemsTree( $language_id, $menu_id = null, $parent_id = null, $itemsTree = [], $withDropdowns = true ) {
		if ($parent_id) {
			$items = self::find()->activeStatus()->andWhere([
				'language_id' => $language_id,
				'parent_id' => $parent_id
			])->orderBy('item_order')->all();
		} else {
			$items = self::find()->activeStatus()->andWhere([
				'language_id' => $language_id,
				'menu_id' => $menu_id,
				'parent_id' => null
			])->orderBy('item_order')->all();
		}
		/** @var MenuContent $item */
		foreach ( $items as $item ) {
			$isSubmenu = $parent_id ? false : self::hasSubmenu($language_id, $item->id);
			$uniqueId = uniqid($item->id . '-dropdown_');
			$url = ($item->main && self::isMainMenu($item->menu_id)) ? ['page/home', 'web' => \Yii::$app->request->get('web'), 'language' => \Yii::$app->request->get('language')] : (
				$isSubmenu ? '#' : (
					$item->content_type == MenuItemRecord::CONTENT_LINK ?
						$item->link_url :
						['page/menu', 'web' => \Yii::$app->request->get('web'), 'language' => \Yii::$app->request->get('language'), 'name' => Inflector::slug(strip_tags($item->title)), 'id' => $item->id]
				)
			);
			/** @noinspection HtmlUnknownTarget */
			$template =  ($withDropdowns && $isSubmenu) ? '<a href="{url}" class="dropdown-button" data-activates="' . $uniqueId . '" data-beloworigin="true" data-constrainwidth="false">{label}</a>' : '<a href="{url}"' . ($item->link_target == MenuItemRecord::TARGET_NEW_WINDOW ? ' target="_blank"' : '') . '>{label}</a>';
			/** @noinspection HtmlUnknownTarget */
			$itemsTree[] = [
				'label' => $item->title . (($withDropdowns && $isSubmenu) ? '<i class="material-icons right">arrow_drop_down</i>' : ''),
				'url' => $url,
				'template' => $template,
				'submenuTemplate' => $withDropdowns ? "\n<ul id=\"" . $uniqueId. "\" class=\"dropdown-content\">\n{items}\n</ul>\n" :  ($isSubmenu == false ? '&nbsp;<i class="material-icons">navigate_next</i>' : '') . "\n<ul class=\"z-depth-1\">\n{items}\n</ul>\n",
				'items' => self::getItemsTree($language_id, null, $item->id, [], $withDropdowns)
			];
		}
		return $itemsTree;
	}

	/**
	 * Returns $url according to given menu_id
	 * @param $id
	 *
	 * @return array|null|string
	 */
	public static function getMenuUrlFromId($id) {
		$menuItem = MenuItemRecord::findOne($id);
		if ($menuItem) {
			$url = ($menuItem->main && self::isMainMenu($menuItem->menu_id)) ? ['page/home', 'web' => \Yii::$app->request->get('web'), 'language' => \Yii::$app->request->get('language')] : (
			$menuItem->content_type == MenuItemRecord::CONTENT_LINK ? $menuItem->link_url :
				['page/menu', 'web' => \Yii::$app->request->get('web'), 'language' => \Yii::$app->request->get('language'), 'name' => Inflector::slug(strip_tags($menuItem->title)), 'id' => $id]
			);
			return $url;
		} else {
			return null;
		}
	}

	/**
	 * Checks if menu has submenu
	 * @param $language_id
	 * @param $parent_id
	 *
	 * @return bool
	 */
	private static function hasSubmenu( $language_id, $parent_id ) {
		$items = self::find()->activeStatus()->andWhere([
			'language_id' => $language_id,
			'parent_id' => $parent_id
		])->count();
		return $items ? true : false;
	}

	/**
	 * Checks if menu is mainmenu
	 * @param $menu_id
	 *
	 * @return bool
	 */
	private static function isMainMenu( $menu_id ) {
		$menuMain = MenuRecord::find()->select('main')->where(['id' => $menu_id])->scalar();
		return $menuMain ? true : false;
	}

	public static function getFirstArticleIdFromCategoryId( $categoryId ) {
		//TODO opravit - není zcela košér - nebere v potaz více webů
		$sql = "( SELECT DISTINCT `content`.`id`
				FROM `content`
				INNER JOIN `article_category` ON article_category.article_id=content.id
				INNER JOIN `category` ON category.id=article_category.category_id
				WHERE (((((((`article_category`.`category_id`=:category_id))
					AND (`content`.`content_type`=:content_type)))
					AND (`content`.`active`=TRUE)) " . (Yii::$app->user->isGuest ? " AND (`content`.`public`=TRUE))" : '') . "
					AND (`content`.`language_id`=:language_id)))
				ORDER BY content.updated_at DESC LIMIT 1";

		return ContentRecord::findBySql($sql, [
			':category_id' => $categoryId,
			':content_type' => ContentRecord::TYPE_ARTICLE,
			':language_id' => FrontEndHelper::getLanguageIdFromAcronym(),
		])->scalar();
	}

	/**
	 * Creates array for breadcrumbs widget
	 * @param $id
	 * @param array $items
	 * @param bool|true $urls
	 *
	 * @return array
	 */
	public static function getBreadCrumbs($id, $items = [], $urls = false)
	{
		/** @var MenuItemRecord $model */
		$model = MenuItemRecord::findOne($id);
		if ($model) {
			$items[] = $urls === true ? ['label' => $model->title, 'url' => $model->content_type == MenuItemRecord::CONTENT_LINK ? ($model->link_url != '#!' ?: null) : ['page/menu', 'web' => \Yii::$app->request->get('web'), 'language' => \Yii::$app->request->get('language'), 'name' => Inflector::slug(strip_tags($model->title)), 'id' => $model->id]] : $model->title;
			if ($model->parent_id) {
				$urls = true;
				return self::getBreadCrumbs($model->parent_id, $items, $urls);
			}
		}
		return array_reverse($items);
	}
}