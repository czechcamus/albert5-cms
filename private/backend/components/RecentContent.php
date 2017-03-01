<?php
/**
 * Created by PhpStorm.
 * User: Pavel
 * Date: 12.8.2015
 * Time: 13:58
 */

namespace backend\components;

use Yii;
use yii\db\ActiveRecord;
use common\models\ContentRecord;
use yii\base\Widget;
use yii\helpers\Inflector;

/**
 * Class RecentContent displays recent content items
 * If property $itemClass is not set, Page will be used
 * If property $itemsCount is not set, 3 items will be displayed
 * @package backend\components
 */
class RecentContent extends Widget
{
	/**
	 * Class name of item
	 * @var string
	 */
	public $itemClass = 'Page';

	/**
	 * Number of items in list
	 * @var int
	 */
	public $itemsCount = 5;

	private $_items = [];

	public function init() {
		parent::init();
		/** @var ActiveRecord $className */
		$className = '\\common\\models\\' . $this->itemClass;
		$query = $className::find();
		if (!Yii::$app->user->can('manager')) {
			$query->andWhere(['created_by' => Yii::$app->user->id]);
		}
		$this->_items = $query->limit($this->itemsCount)->orderBy(['updated_at' => SORT_DESC])->all();
	}

	public function run() {
		$controllerId = Inflector::camel2id($this->itemClass);
		$translatedWordItems = $this->itemClass == 'Page' ? \Yii::t('back', 'pages') : \Yii::t('back', 'articles');
		$translatedWordItems2 = $this->itemClass == 'Page' ? \Yii::t('back', 'of pages') : \Yii::t('back', 'of articles');
		$contentType = $this->itemClass == 'Page' ? ContentRecord::TYPE_PAGE : ContentRecord::TYPE_ARTICLE;
		return $this->render('recentContent', [
			'items' => $this->_items,
			'controllerId' => $controllerId,
			'translatedWordItems' => $translatedWordItems,
			'translatedWordItems2' => $translatedWordItems2,
			'contentType' => $contentType
		]);
	}
}