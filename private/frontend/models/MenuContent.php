<?php
/**
 * Created by PhpStorm.
 * User: Pavel
 * Date: 19.8.2015
 * Time: 12:50
 */

namespace frontend\models;


use common\models\MenuItemRecord;
use common\models\MenuRecord;
use frontend\utilities\FrontEndHelper;
use yii\helpers\Inflector;

/**
 * Class MenuContent extends MenuItemRecord for frontend purposes
 * @package frontend\models
 */
class MenuContent extends MenuItemRecord {
	/**
	 * Gets category data for search functionality
	 *
	 * @param $q string search query
	 *
	 * @return MenuContent[]
	 */
	public function searchCategory( $q ) {
		$q     = htmlentities( $q );
		$idw   = FrontEndHelper::getWebIdFromTextId( \Yii::$app->request->get( 'web',
			\Yii::$app->params['defaultWeb'] ) );
		$idl   = FrontEndHelper::getLanguageIdFromAcronym();
		$query = self::find()
		             ->innerJoin( 'menu_item_content', 'menu_item_content.menu_item_id=menu_item.id' )
		             ->innerJoin( 'category', 'category.id=menu_item_content.category_id' )
		             ->innerJoin( 'menu', 'menu.id=menu_item.menu_id' )
		             ->where( [ 'menu.web_id' => $idw ] )
		             ->andWhere( [ 'menu_item.language_id' => $idl ] )
		             ->andWhere( [ 'menu_item.active' => true ] )
		             ->andWhere( [ 'or', [ 'like', 'menu_item.title', $q ], [ 'like', 'category.description', $q ] ] )
		             ->orderBy( [ 'category.updated_at' => SORT_DESC ] )
		             ->all();

		return $query;
	}

	/**
	 * Gets content data for search functionality
	 *
	 * @param $q string search query
	 *
	 * @return MenuContent[]
	 */
	public function searchContent( $q ) {
		$q     = htmlentities( $q );
		$idw   = FrontEndHelper::getWebIdFromTextId( \Yii::$app->request->get( 'web',
			\Yii::$app->params['defaultWeb'] ) );
		$idl   = FrontEndHelper::getLanguageIdFromAcronym();
		$query = self::find()
		             ->innerJoin( 'menu_item_content', 'menu_item_content.menu_item_id=menu_item.id' )
		             ->innerJoin( 'content', 'content.id=menu_item_content.content_id' )
		             ->innerJoin( 'menu', 'menu.id=menu_item.menu_id' )
		             ->where( [ 'menu.web_id' => $idw ] )
		             ->andWhere( [ 'menu_item.language_id' => $idl ] )
		             ->andWhere( [ 'menu_item.active' => true ] )
		             ->andWhere( [
			             'or',
			             [ 'like', 'menu_item.title', $q ],
			             [ 'like', 'content.perex', $q ],
			             [ 'like', 'content.description', $q ]
		             ] )
		             ->orderBy( [ 'content.updated_at' => SORT_DESC ] )
		             ->all();

		return $query;
	}

	/**
	 * Gets content data for tag functionality
	 *
	 * @param $tag string search query
	 *
	 * @return MenuContent[]
	 */
	public function searchTags( $tag ) {
		$idw   = FrontEndHelper::getWebIdFromTextId( \Yii::$app->request->get( 'web',
			\Yii::$app->params['defaultWeb'] ) );
		$idl   = FrontEndHelper::getLanguageIdFromAcronym();
		$query = self::find()
		             ->innerJoin( 'menu_item_content', 'menu_item_content.menu_item_id=menu_item.id' )
		             ->innerJoin( 'content', 'content.id=menu_item_content.content_id' )
		             ->innerJoin( 'menu', 'menu.id=menu_item.menu_id' )
		             ->innerJoin( 'content_tag', 'content_tag.content_id=content.id' )
		             ->innerJoin( 'tag', 'tag.id=content_tag.tag_id' )
		             ->where( [ 'menu.web_id' => $idw ] )
		             ->andWhere( [ 'menu_item.language_id' => $idl ] )
		             ->andWhere( [ 'menu_item.active' => true ] )
		             ->andWhere( [ 'tag.name' => $tag ] )
		             ->orderBy( [ 'content.updated_at' => SORT_DESC ] )
		             ->all();

		return $query;
	}

	/**
	 * Gets url actual item
	 * @return array|string
	 */
	public function getUrl() {
		$pos = strpos( $this->title, ';' );
		if ( $pos === false ) {
			$itemTitle = $this->title;
		} else {
			list( $itemTitle, $icon, $subtitle ) = explode( ';', $this->title );
		}
		$url = $this->content_type == MenuItemRecord::CONTENT_LINK ? $this->link_url : [
			'page/menu',
			'web'      => \Yii::$app->request->get( 'web' ),
			'language' => \Yii::$app->request->get( 'language' ),
			'name'     => Inflector::slug( strip_tags( $itemTitle ) ),
			'id'       => $this->id
		];

		return $url;
	}

	/**
	 * Gets url array for back link
	 * @return array
	 */
	public function getBackUrl() {
		$url = [
			'page/menu',
			'web'      => \Yii::$app->request->get( 'web' ),
			'language' => \Yii::$app->request->get( 'language' ),
			'name'     => Inflector::slug( strip_tags( $this->parentItem->title ) ),
			'id'       => $this->parent_id
		];

		return $url;
	}

	/**
	 * Creates menu tree
	 *
	 * @param string $menuTextId menu text id
	 * @param null $parentId parent menu item id
	 * @param string $subMenuStyle submenu style: none, dropdown, collapsible are available
	 * @param array $dropdownOptions tag data attributes for dropdowns
	 * @param string $collapsibleHeaderClasses css classes for collapsible header
	 * @param array $itemsTree
	 * @param bool $labelIcon will be displayed?
	 * @param string $templatePrefix
	 *
	 *
	 * @return array
	 */
	public static function getItemsTree(
		$menuTextId = 'mainmenu',
		$parentId = null,
		$subMenuStyle = 'none',
		$dropdownOptions = [],
		$collapsibleHeaderClasses = '',
		$itemsTree = [],
		$labelIcon = true,
		$templatePrefix = ''
	) {
		$menuId            = FrontEndHelper::getMenuIdFromTextId( $menuTextId );
		$languageId        = FrontEndHelper::getLanguageIdFromAcronym();
		$andWhereCondition = [ 'language_id' => $languageId ];
		if ( $parentId ) {
			$andWhereCondition['parent_id'] = $parentId;
		} else {
			$andWhereCondition['menu_id']   = $menuId;
			$andWhereCondition['parent_id'] = null;
		}
		/** @noinspection PhpUndefinedMethodInspection */
		$items = self::find()->activeStatus()->andWhere( $andWhereCondition )->orderBy( 'item_order' )->all();
		foreach ( $items as $item ) {
			$uniqueId       = '';
			$firstLevelMenu = $parentId ? false : true;
			$hasSubmenu     = self::hasSubmenu( $languageId, $item->id );
			if ( $subMenuStyle == 'dropdown' ) {
				$uniqueId = uniqid( $item->id . '-dropdown_' );
			}
			$url = ( $item->main && self::isMainMenu( $item->menu_id ) ) ? [
				'page/home',
				'web'      => \Yii::$app->request->get( 'web' ),
				'language' => \Yii::$app->request->get( 'language' )
			] : ( $item->content_type == MenuItemRecord::CONTENT_LINK ?
				$item->link_url :
				[
					'page/menu',
					'web'      => \Yii::$app->request->get( 'web' ),
					'language' => \Yii::$app->request->get( 'language' ),
					'name'     => Inflector::slug( strip_tags( $item->title ) ),
					'id'       => $item->id
				]
			);
			if ( $hasSubmenu && $subMenuStyle == 'dropdown' ) {
				$template        = $templatePrefix . '<a href="{url}"' . ( $firstLevelMenu ? ' class="dropdown-button" data-activates="' . $uniqueId . '"' . self::renderDropdownOptions( $dropdownOptions ) : '' ) . '>{label}</a>';
				$labelAdd        = ( $firstLevelMenu && $labelIcon ) ? "&nbsp;<i class=\"material-icons right\">arrow_drop_down</i>" : '';
				$subMenuTemplate = $firstLevelMenu ? "\n<ul id=\"" . $uniqueId . "\" class=\"dropdown-content\">\n{items}\n</ul>\n" : '';
			} elseif ( $hasSubmenu && $subMenuStyle == 'collapsible' ) {
				$template        = $templatePrefix . '<a href="{url}"' . ( $firstLevelMenu ? ' class="collapsible-header' . ( $collapsibleHeaderClasses ? ' ' . $collapsibleHeaderClasses : '' ) . '"' : '' ) . '>{label}</a>';
				$labelAdd        = ( $firstLevelMenu && $labelIcon ) ? "&nbsp;<i class=\"material-icons right\">arrow_drop_down</i>" : '';
				$subMenuTemplate = $firstLevelMenu ? "\n<div class=\"collapsible-body\" style=\"display: block\"><ul>\n{items}\n</ul></div>\n" : '';
			} else {
				$template        = $templatePrefix . '<a href="{url}"' . ( $item->link_target == MenuItemRecord::TARGET_NEW_WINDOW ? ' target="_blank"' : '' ) . '>{label}</a>';
				$labelAdd        = ( ( $firstLevelMenu || ! $hasSubmenu || ! $labelIcon ) ? '' : "&nbsp;<i class=\"material-icons right\">navigate_next</i>" );
				$subMenuTemplate = "\n<ul class=\"z-depth-1\">\n{items}\n</ul>\n";
			}
			$itemsTree[] = [
				'label'           => $item->title . $labelAdd,
				'url'             => $url,
				'template'        => $template,
				'submenuTemplate' => $subMenuTemplate,
				'items'           => self::getItemsTree( $menuTextId, $item->id, $subMenuStyle, $dropdownOptions,
					$collapsibleHeaderClasses, [] )
			];
		}

		return $itemsTree;
	}

	/**
	 * Returns $url according to given menu_id
	 *
	 * @param $id
	 *
	 * @return array|null|string
	 */
	public static function getMenuUrlFromId( $id ) {
		$menuItem = MenuItemRecord::findOne( $id );
		if ( $menuItem ) {
			$url = ( $menuItem->main && self::isMainMenu( $menuItem->menu_id ) ) ? [
				'page/home',
				'web'      => \Yii::$app->request->get( 'web' ),
				'language' => \Yii::$app->request->get( 'language' )
			] : (
			$menuItem->content_type == MenuItemRecord::CONTENT_LINK ? $menuItem->link_url :
				[
					'page/menu',
					'web'      => \Yii::$app->request->get( 'web' ),
					'language' => \Yii::$app->request->get( 'language' ),
					'name'     => Inflector::slug( strip_tags( $menuItem->title ) ),
					'id'       => $id
				]
			);

			return $url;
		} else {
			return null;
		}
	}

	/**
	 * Checks if menu has submenu
	 *
	 * @param $language_id
	 * @param $parent_id
	 *
	 * @return bool
	 */
	private static function hasSubmenu( $language_id, $parent_id ) {
		$items = self::find()->activeStatus()->andWhere( [
			'language_id' => $language_id,
			'parent_id'   => $parent_id
		] )->count();

		return $items ? true : false;
	}

	/**
	 * Checks if menu is mainmenu
	 *
	 * @param $menu_id
	 *
	 * @return bool
	 */
	private static function isMainMenu( $menu_id ) {
		$menuMain = MenuRecord::find()->select( 'main' )->where( [ 'id' => $menu_id ] )->scalar();

		return $menuMain ? true : false;
	}

	/**
	 * Creates array for breadcrumbs widget
	 *
	 * @param $id
	 * @param array $items
	 * @param bool|true $urls
	 *
	 * @return array
	 */
	public static function getBreadCrumbs( $id, $items = [], $urls = false ) {
		/** @var MenuItemRecord $model */
		$model = MenuItemRecord::findOne( $id );
		if ( $model ) {
			$pattern = "/(.*) (<i.*i>)/i";
			$replacement = "$1";
			$items[] = $urls === true ? [
				'label' => preg_replace($pattern, $replacement, $model->title),
				'url'   => $model->content_type == MenuItemRecord::CONTENT_LINK ? ( $model->link_url != '#!' ?: null ) : [
					'page/menu',
					'web'      => \Yii::$app->request->get( 'web' ),
					'language' => \Yii::$app->request->get( 'language' ),
					'name'     => Inflector::slug( strip_tags( $model->title ) ),
					'id'       => $model->id
				]
			] : $model->title;
			if ( $model->parent_id ) {
				$urls = true;

				return self::getBreadCrumbs( $model->parent_id, $items, $urls );
			}
		}

		return array_reverse( $items );
	}

	/**
	 * Renders options for materialize dropdown
	 *
	 * @param $options
	 *
	 * @return string
	 */
	public static function renderDropdownOptions( $options ) {
		$defaultOptions = [
			'beloworigin' => true,
		];
		$options        = array_merge( $defaultOptions, $options );
		$optionString   = '';
		foreach ( $options as $key => $value ) {
			$optionString .= ' data-' . $key . '="' . ( is_bool( $value ) ? ( $value === true ? 'true' : 'false' ) : $value ) . '"';
		}

		return $optionString;
	}
}