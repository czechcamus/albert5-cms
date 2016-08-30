<?php
/**
 * Created by PhpStorm.
 * User: Pavel
 * Date: 7.2.2015
 * Time: 14:53
 */

namespace backend\components;


use common\models\LanguageRecord;
use Yii;
use yii\base\Widget;
use yii\helpers\Html;
use yii\helpers\Url;

class LanguageButtonDropdown extends Widget
{
	public $routeBase = [];

	private $_label = '';
	private $_items = [];

	public function init()
	{
		//parent::init();
		$session = Yii::$app->session;
		if (!$session['language_id'])
			$session['language_id'] = LanguageRecord::getMainLanguageId();

		$langValues = LanguageRecord::getLanguageValues($session['language_id']);
		$this->_label = Html::img('@web/images/flags/' . $langValues['acronym'] . '.gif', ['alt' => Yii::t('back', 'image - flag')])
		         . ' ' . $langValues['title'];
		$languages = LanguageRecord::getLanguages(true);
		foreach ($languages as $language) {
			/** @var LanguageRecord $language */
			$this->_items[] = [
				'label' => Html::img('@web/images/flags/' . $language->acronym . '.gif', ['alt' => Yii::t('back', 'image - flag')])
				           . ' ' . $language->title,
				'url' => Yii::$app->urlManager->createUrl(['site/switch-language', 'id' => $language->id])
			];
		}

		Url::remember($this->routeBase);
	}

	public function run()
	{
		return $this->render('languageButtonDropdown', ['label' => $this->_label, 'items' => $this->_items]);
	}
}