<?php
/**
 * Created by PhpStorm.
 * User: Pavel
 * Date: 11.9.2015
 * Time: 22:42
 */

namespace frontend\components;


use frontend\models\MenuContent;
use yii\base\InvalidParamException;
use yii\bootstrap\Widget;

/**
 * Class SiblingMenus displays sibling menus items list
 * @property $parentMenuItemId id of parent menu item - required
 * @property $currentMenuItemId id of current menu item - required
 * @property $title menu title
 * @property $parentMenuTitle parent menu title
 * @package frontend\components
 */
class SiblingMenus extends Widget
{
	/** @var int parent menu item id */
	public $parentMenuItemId;

	/** @var int current menu item id */
	public $currentMenuItemId;
	
	/** @var string title of menu */
	public $title = '';
	
	/** @var string title of parent menu */
	public $parentMenuTitle = '';

	private $_items;
	
	public function init() {
		parent::init();
		if($this->parentMenuItemId) {
			if ($this->currentMenuItemId) {
				$query = MenuContent::find()->where(['and', 'active=1', 'parent_id=' . $this->parentMenuItemId, 'id!=' . $this->currentMenuItemId]);
				if (\Yii::$app->user->isGuest) {
					$query->andWhere([
						'public' => 1
					]);
				}
				$this->_items = $query->orderBy(['item_order' => SORT_ASC])->all();
				$this->title = $this->title ?: \Yii::t('front', 'Next pages in menu'); 
			} else {
				throw new InvalidParamException(\Yii::t('front', 'No required parameter given') . ' - currentMenuItemId');
			}
		} else {
			throw new InvalidParamException(\Yii::t('front', 'No required parameter given') . ' - parentMenuItemId');
		}
	}

	public function run() {
		return $this->render('siblingMenus', [
			'items' => $this->_items,
			'title' => $this->title,
			'parentMenuTitle' => $this->parentMenuTitle
		]);
	}
}