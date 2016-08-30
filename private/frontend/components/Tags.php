<?php
/**
 * Created by PhpStorm.
 * User: Pavel
 * Date: 12.9.2015
 * Time: 23:15
 */

namespace frontend\components;


use common\models\LanguageRecord;
use yii\base\Widget;
use yii\db\Query;

class Tags extends Widget
{
	public $contentId = null;

	private $_items;

	public function init() {
		parent::init();
		$language_id = LanguageRecord::find()->select('id')->where(['acronym' => \Yii::$app->language])->scalar();
		if ($this->contentId === null) {
			$subQuery = (new Query())->select('*')->from('tag')->where('frequency>0')->andWhere(['language_id' => $language_id])->orderBy('frequency DESC')->limit(\Yii::$app->params['defaultTagsCount']);
			$this->_items = (new Query())->select('*')->from(['tag' => $subQuery])->orderBy('name')->all();
		} else {
			$this->_items = (new Query())->select('*')->from('tag, content_tag')->where('tag.id=content_tag.tag_id')->andWhere(['content_tag.content_id' => $this->contentId])->orderBy('tag.name')->all();
		}
	}

	public function run() {
		return $this->render('tags', [
			'items' => $this->_items,
			'contentId' => $this->contentId
		]);
	}
}