<?php
/**
 * Created by PhpStorm.
 * User: Pavel
 * Date: 4.2.2015
 * Time: 21:18
 */

namespace backend\models;


use common\models\CategoryRecord;
use common\models\ContentRecord;
use common\models\LanguageRecord;
use common\models\LayoutRecord;
use common\models\MenuItemContent;
use common\models\MenuRecord;
use common\models\MenuItemRecord;
use common\models\WebRecord;
use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;

class MenuItemForm extends Model
{
	/** @var integer Id of web */
	public $web_id;
	/** @var integer Id of menu */
	public $menu_id;
	/** @var integer Id of parent menu item */
	public $parent_id;
	/** @var integer actual menu item id */
	public $item_id;
	/** @var integer Id of language */
	public $language_id;
	/** @var string order of menu item Ids delimited by comma */
	public $item_order;
	/** @var string title of menu item */
	public $title;
	/** @var integer Id of content item type */
	public $content_type;
	/** @var string link url */
	public $link_url;
	/** @var integer link target */
	public $link_target;
	/** @var integer Id of related content */
	public $content_id;
	/** @var integer Id of related layout */
	public $layout_id;
	/** @var array boxes of properties */
	public $boxes;

	const PROPERTY_MAIN = 1;
	const PROPERTY_ACTIVE = 2;
	const PROPERTY_PUBLIC = 3;

	/**
	 * MenuItemForm constructor
	 * @param integer $menu_id
	 * @param integer $parent_id
	 * @param integer $item_id
	 */
	public function __construct($menu_id = null, $parent_id = null, $item_id = null)
	{
		parent::__construct();
		if ($item_id) {
			/** @var $menuItemRecord MenuItemRecord */
			$menuItemRecord = MenuItemRecord::findOne($item_id);
			$this->menu_id = $menuItemRecord->menu_id;
			$this->parent_id = $menuItemRecord->parent_id;
			$this->item_id = $menuItemRecord->id;
			$this->language_id = $menuItemRecord->language_id;
			$this->item_order = $menuItemRecord->item_order;
			$this->title = $menuItemRecord->title;
			$this->content_type = $menuItemRecord->content_type;
			$this->link_url = $menuItemRecord->link_url;
			$this->link_target = $menuItemRecord->link_target;

			switch ($this->content_type) {
				case MenuItemRecord::CONTENT_PAGE:
					$content_id = isset($menuItemRecord->content->id) ? $menuItemRecord->content->id : null;
					break;
				case MenuItemRecord::CONTENT_CATEGORY:
					$content_id = isset($menuItemRecord->category->id) ? $menuItemRecord->category->id : null;
					break;
				default:
					$content_id = null;
					break;
			}
			$this->content_id = $content_id;
			$this->layout_id = $menuItemRecord->layout_id;

			if ($menuItemRecord->main)
				$this->boxes[] = self::PROPERTY_MAIN;
			if ($menuItemRecord->active)
				$this->boxes[] = self::PROPERTY_ACTIVE;
			if ($menuItemRecord->public)
				$this->boxes[] = self::PROPERTY_PUBLIC;
		} else {
			if ($menu_id)
				$this->menu_id = $menu_id;
			$this->parent_id = $parent_id;

			$session = Yii::$app->session;
			if (!$session['language_id'])
				$session['language_id'] = LanguageRecord::getMainLanguageId();
			$this->language_id = $session['language_id'];

			$this->boxes[] = self::PROPERTY_ACTIVE;
			$this->boxes[] = self::PROPERTY_PUBLIC;
		}
	}

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			['item_order', 'safe', 'on' => ['index']],
			[['title', 'content_type'], 'required', 'on' => ['create', 'createFromContent', 'update']],
			[['content_type', 'link_target'], 'integer', 'on' => ['create', 'createFromContent', 'update']],
			['content_id', 'validateContentId', 'on' => ['create', 'createFromContent', 'update']],
			['layout_id', 'validateLayoutId', 'on' => ['create', 'createFromContent', 'update']],
			[['title', 'link_url'], 'string', 'max' => 255, 'on' => ['create', 'createFromContent', 'update']],
			[['web_id', 'menu_id'], 'required', 'on' => ['createFromContent']],
			[['parent_id', 'language_id', 'web_id', 'menu_id', 'item_id', 'boxes', 'item_order'], 'safe']
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'title' => Yii::t('back', 'Title'),
			'web_id' => Yii::t('back', 'Web'),
			'menu_id' => Yii::t('back', 'Menu'),
			'parent_id' => Yii::t('back', 'Parent Item'),
			'item_id' => Yii::t('back', 'Actual Item'),
			'language_id' => Yii::t('back', 'Language'),
			'content_type' => Yii::t('back', 'Content Type'),
			'content_id' => Yii::t('back', 'Content'),
			'layout_id' => Yii::t('back', 'Layout'),
			'link_url' => Yii::t('back', 'Link Url'),
			'link_target' => Yii::t('back', 'Link Target'),
			'boxes' => Yii::t('back', 'Properties'),
			'item_order' => Yii::t('back', 'Order')
		];
	}

	/**
	 * Return items array for sortable input
	 * @return array $itemsArray
	 */
	public function getMenuItems()
	{
		$query = MenuItemRecord::find()->andWhere('menu_id = :mid', [':mid' => $this->menu_id])
			->andWhere('language_id = :lid', [':lid' => $this->language_id])->orderBy('item_order');
		if ($this->parent_id) {
			$query->andWhere('parent_id = :pid', [':pid' => $this->parent_id]);
		} else {
			$query->andWhere('ISNULL(parent_id)');
		}

		$items = $query->all();
		$itemsArray = [];
		foreach ($items as $item) {
			/** @var $item \common\models\MenuItemRecord */
			$itemsArray[$item->id] = [
				'content' => $item->title . '<span class="pull-right">'
	                . $this->getControlLink('update', $item->id) . ' '
					. ($item->isDeletable() ? $this->getControlLink('delete', $item->id) : '') . ' '
					. ($item->hasItems() ? $this->getControlLink('items', $item->id) : '') . '</span>',
				'options' => [
					'class' => $item->active == 0 ? 'inactive' : ($item->public == 0 ? 'nonpublic' : null)
				]
			];
		}

		return $itemsArray;
	}

	/**
	 * Save MenuItem record model and relations
	 * @param bool $insert
	 * @throws \Exception
	 */
	public function saveMenuItem($insert = true)
	{
		$menuItemRecord = new MenuItemRecord();
		if ($this->item_id)
			$menuItemRecord->id = $this->item_id;
		$menuItemRecord->isNewRecord = $insert;
		$menuItemRecord->attributes = $this->toArray();
		if ($menuItemRecord->content_type == MenuItemRecord::CONTENT_LINK) {
			$menuItemRecord->layout_id = null;
		} else {
			$menuItemRecord->link_url = null;
			$menuItemRecord->link_target = null;
		}
		$menuItemRecord->main = (is_array($this->boxes) && in_array(self::PROPERTY_MAIN, $this->boxes)) ? 1 : 0;
		$menuItemRecord->active = (is_array($this->boxes) && in_array(self::PROPERTY_ACTIVE, $this->boxes)) ? 1 : 0;
		$menuItemRecord->public = (is_array($this->boxes) && in_array(self::PROPERTY_PUBLIC, $this->boxes)) ? 1 : 0;
		if ($menuItemRecord->save()) {
			if ($menuItemContent = MenuItemContent::findOne(['menu_item_id' => $menuItemRecord->id])) {
				$insert = false;
			} else {
				$menuItemContent = new MenuItemContent();
				$menuItemContent->menu_item_id = $menuItemRecord->id;
				$insert = true;
			}
			if ($this->content_type != MenuItemRecord::CONTENT_LINK) {
				if ($this->content_type == MenuItemRecord::CONTENT_PAGE) {
					$menuItemContent->content_id = $this->content_id;
					$menuItemContent->category_id = null;
				} else {
					$menuItemContent->category_id = $this->content_id;
					$menuItemContent->content_id = null;
				}
				$menuItemContent->save();
			} else {
				if (!$insert) // Content record exists in table
					$menuItemContent->delete();
			}
		}
	}

	/**
	 * Deletes menu item record and related content
	 * @throws \Exception
	 */
	public function deleteMenuItem()
	{
		/** @var MenuItemRecord $menuItemRecord */
		if ($menuItemRecord = MenuItemRecord::findOne($this->item_id)) {
			$menuItemRecord->delete();
		}
	}

	/**
	 * @return MenuRecord object
	 */
	public function getMenu()
	{
		return MenuRecord::findOne($this->menu_id);
	}

	/**
	 * @param integer $cid content type
	 * @return string
	 */
	public static function getContentLabel($cid)
	{
		$itemTypesArray = self::getContentTypes();
		return $itemTypesArray[$cid];
	}

	/**
	 * @return array drop down list items
	 */
	public function getContentTypes()
	{
		return [
			MenuItemRecord::CONTENT_PAGE => Yii::t('back', 'Page'),
			MenuItemRecord::CONTENT_CATEGORY => Yii::t('back', 'Category'),
			MenuItemRecord::CONTENT_LINK => Yii::t('back', 'Link')
		];
	}

	/**
	 * @return array drop down list items
	 */
	public function getLinkTargets()
	{
		return [
			MenuItemRecord::TARGET_THIS_WINDOW => Yii::t('back', 'This Window/Tab'),
			MenuItemRecord::TARGET_NEW_WINDOW => Yii::t('back', 'New Window/Tab')
		];
	}

	/**
	 * Validates content_id
	 * @param $attribute
	 */
	public function validateContentId($attribute)
	{
		if (($this->content_type != MenuItemRecord::CONTENT_LINK) && (!$this->$attribute))
			$this->addError($attribute, Yii::t('back', 'Content must be selected.'));
	}

	/**
	 * Validates layout_id
	 * @param $attribute
	 */
	public function validateLayoutId($attribute)
	{
		if (($this->content_id > 0) && (!$this->$attribute))
			$this->addError($attribute, Yii::t('back', 'Layout must be selected.'));
	}

	/**
	 * Gets parent items tree for dropdown
	 * @param integer $pid
	 * @param integer $i
	 *
	 * @return array
	 */
	public function getParentItems($pid = null, $i = 0)
	{
		$listItems = [];
		$query = MenuItemRecord::find()->where('main = 0 AND menu_id = :mid AND language_id = :lid',
			[':mid' => $this->menu_id, ':lid' => $this->language_id]);
		if ($pid === null)
			$query->andWhere('ISNULL(parent_id)');
		else
			$query->andWhere('parent_id = :pid', [':pid' => $pid]);
		if ($this->scenario == 'update')
			$query->andWhere('id != :id', [':id' => $this->item_id]);
		$items = $query->all();
		foreach ($items as $item) {
			/** @var $item \common\models\MenuItemRecord */
			$listItems[$item->id] = str_repeat('=', $i) . ($i > 0 ? ' ' : '') . $item->title;
			if ($item->hasItems()) {
				$listItems = ArrayHelper::merge($listItems, $this->getParentItems($item->id, ++$i));
				--$i;
			}
		}
		return $listItems;
	}

	/**
	 * @param string $controlType type of control (update, delete, items)
	 * @param integer $id actual menu item id
	 * @return string control link
	 */
	protected function getControlLink($controlType, $id = null)
	{
		$updateUrl = ['menu-item/update', 'mid' => $this->menu_id];
		$deleteUrl = ['menu-item/delete', 'mid' => $this->menu_id];
		$itemsUrl = ['menu-item/index', 'mid' => $this->menu_id];
		if ($id)
			$updateUrl['id'] = $deleteUrl['id'] = $itemsUrl['pid'] = $id;
		if ($this->parent_id != null)
			$updateUrl['pid'] = $deleteUrl['pid'] = $this->parent_id;
		$controlArray = [
			'update' => ['pencil', [
				'value' => Url::to($updateUrl),
				'class' => 'showModalButton btn btn-link',
				'title' => Yii::t('back', 'Update menu item'),
				'style' => 'padding: 0'
			]],
			'delete' => ['trash', $deleteUrl, [
				'title' => Yii::t('back', 'Delete menu item'),
				'data-confirm' => Yii::t('back', 'Are you sure, you want to delete this item?'),
				'style' => 'padding: 0'
			]],
			'items' => ['menu-hamburger', $itemsUrl, [
				'title' => Yii::t('back', 'Lower level items'),
				'style' => 'padding: 0'
			]]
		];
		if ($controlType == 'update') {
			$tag = Html::button('<span class="glyphicon glyphicon-' . $controlArray[$controlType][0] . '" aria-hidden="true"></span>',
				$controlArray[$controlType][1]);
		} else {
			$tag = Html::a('<span class="glyphicon glyphicon-' . $controlArray[$controlType][0] . '" aria-hidden="true"></span>',
				$controlArray[$controlType][1], $controlArray[$controlType][2]);
		}
		return $tag;
	}

	/**
	 * Returns content list options for dropdown
	 * @return array
	 */
	public function getContentListOptions() {
		$session = Yii::$app->session;
		if (!$session['language_id'])
			$session['language_id'] = LanguageRecord::getMainLanguageId();

		$items = [];
		switch ($this->content_type) {
			case MenuItemRecord::CONTENT_PAGE:
				$items = ArrayHelper::map(ContentRecord::find()->where([
					'language_id' => $session['language_id'],
					'content_type' => ContentRecord::TYPE_PAGE
				])->activeStatus()->orderBy('updated_at DESC')->all(), 'id', 'title');
				break;
			case MenuItemRecord::CONTENT_CATEGORY:
				$items = ArrayHelper::map(CategoryRecord::find()->where([
					'language_id' => $session['language_id'],
					'category_type' => CategoryRecord::TYPE_CATEGORY
				])->activeStatus()->orderBy('updated_at DESC')->all(), 'id', 'title');
				break;
			default:
				break;
		}
		return $items;
	}

	/**
	 * Returns layout list options for dropdown
	 * @return array
	 */
	public function getLayoutListOptions() {
		$session = Yii::$app->session;
		if (!$session['language_id'])
			$session['language_id'] = LanguageRecord::getMainLanguageId();

		$items = [];
		switch ($this->content_type) {
			case MenuItemRecord::CONTENT_PAGE:
				$items = ArrayHelper::map( LayoutRecord::find()->where( [
					'content' => LayoutRecord::CONTENT_PAGE
				] )->activeStatus()->orderBy( [ 'main' => SORT_DESC ] )->all(), 'id', 'title' );
				break;
			case MenuItemRecord::CONTENT_CATEGORY:
				$items = ArrayHelper::map( LayoutRecord::find()->where( [
					'content' => LayoutRecord::CONTENT_CATEGORY
				] )->activeStatus()->orderBy( [ 'main' => SORT_DESC ] )->all(), 'id', 'title' );
				break;
			default:
				break;
		}

		return $items;
	}

	/**
	 * Returns web list options for dropdown
	 * @return array
	 */
	public function getWebListOptions() {
		return ArrayHelper::map(WebRecord::find()->activeStatus()->all(), 'id', 'title');
	}

	/**
	 * Returns parent menu item
	 * @return MenuItemRecord
	 */
	public function getParentItem() {
		return MenuItemRecord::findOne($this->parent_id);
	}
}