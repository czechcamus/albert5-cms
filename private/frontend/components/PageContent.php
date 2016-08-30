<?php
/**
 * Created by PhpStorm.
 * User: Pavel
 * Date: 8.9.2015
 * Time: 14:06
 */

namespace frontend\components;


use common\models\ContentRecord;
use yii\base\InvalidConfigException;
use yii\base\Widget;

/**
 * Class PageContent displays page content in given view file
 * @property $pageId integer is Id of page with content
 * @property $viewName string is name of view file
 * @package frontend\components
 */
class PageContent extends Widget
{
	/** @var integer $pageId */
	public $pageId;

	/** @var string $viewName */
	public $viewName;

	private $_item;

	public function init() {
		parent::init();
		if ($this->pageId) {
			$this->_item = ContentRecord::findOne($this->pageId);
		} else {
			throw new InvalidConfigException( \Yii::t('front', 'No required parameter given') . ' - pageId' );
		}
		if (!$this->viewName) {
			throw new InvalidConfigException( \Yii::t('front', 'No required parameter given') . ' - viewName' );
		}
	}

	public function run() {
		return $this->render($this->viewName, [
			'item' => $this->_item
		]);
	}
}