<?php
/**
 * Created by PhpStorm.
 * User: Pavel
 * Date: 14.2.2015
 * Time: 10:15
 */

namespace backend\components;


use common\models\MenuItemRecord;
use Yii;
use yii\base\Widget;
use yii\helpers\Html;

class ParentItemsTree extends Widget
{
	public $parent_id = '';
	public $menu_id;

	private $_tree = [];
	private $_iterator = 0;

	public function init()
	{
		parent::init();
		$this->getTree();
	}

	public function run()
	{
		$links = array_reverse($this->_tree);
		return $this->render('parentItemsTree', compact('links'));
	}

	public function getTree()
	{
		/** @var MenuItemRecord $menuItem */
		$menuItem = MenuItemRecord::findOne($this->parent_id);
		if ($menuItem) {
			++$this->_iterator;
			$linkRoute = ['menu-item/index'];
			if ($menuItem->parent_id)
				$linkRoute['pid'] = $menuItem->parent_id;
			$linkTitle = $menuItem->parent_id ? $menuItem->title . ' - ' . Yii::t('back', 'level') : Yii::t('back', 'Top level');
			$this->_tree[] = Html::a($linkTitle, $linkRoute, ['title' => Yii::t('back', $linkTitle)]);
			if ($menuItem->parent_id) {
				$this->parent_id = $menuItem->parent_id;
				$this->getTree();
			}
		}
	}
}