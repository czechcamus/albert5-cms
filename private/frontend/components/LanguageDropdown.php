<?php
/**
 * Created by PhpStorm.
 * User: Pavel
 * Date: 14.9.2015
 * Time: 10:55
 */

namespace frontend\components;


use common\models\LanguageRecord;
use yii\base\InvalidConfigException;
use yii\base\Widget;

/**
 * Class LanguageDropdown displays language dropdown item options
 * @property $dropdownId string css Id for dropdown
 * @package frontend\components
 */
class LanguageDropdown extends Widget
{
	public $dropdownId;

	private $_items;

	public function init() {
		parent::init();
		if ($this->dropdownId) {
			$this->_items = LanguageRecord::find()->activeStatus()->all();
		} else {
			throw new InvalidConfigException(\Yii::t('front', 'No required parameter given') . ' - dropdownId');
		}
	}

	public function run() {
		return $this->render('languageDropdown', [
			'items' => $this->_items,
			'dropdownId' => $this->dropdownId
		]);
	}
}