<?php
/**
 * Created by PhpStorm.
 * User: Pavel
 * Date: 30.8.2016
 * Time: 15:04
 */

namespace frontend\modules\install\models;


use frontend\modules\install\Module;
use yii\base\Model;

class WebForm extends Model
{
	// web
	public $webTitle;
	public $webUrl = 'main';
	public $webActive = 1;
	public $webMain = 1;
	public $webPublic = 1;
	public $webTheme = 'basic';

	// language
	public $languageAcronym;
	public $languageActive = 1;
	public $languageMain = 1;
	public $sourceLanguage = 'en';

	// others
	public $newsMailTitle;
	public $webOwner;
	public $googleMapKey;

	const ENGLISH_LANGUAGE = 'en';
	const CZECH_LANGUAGE = 'cs';
	const GERMAN_LANGUAGE = 'de';

	/**
	 * @inheritdoc
	 */
	public function rules() {
		return [
			[['webTitle', 'languageAcronym', 'webOwner'], 'required'],
			[['newsMailTitle', 'googleMapKey'], 'safe']
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels() {
		return [
			'webTitle' => Module::t('inst', 'Name of main web'),
			'languageAcronym' => Module::t('inst', 'Main language (frontend and backend)'),
			'webOwner' => Module::t('inst', 'Owner of web)'),
			'newsMailTitle' => Module::t('inst', 'Title of newsletter mail (mail subject)'),
			'googleMapKey' => Module::t('inst', 'Key for displaying google maps')
		];
	}

	/**
	 * Language options for dropdowns
	 * @return array
	 */
	public function getLanguageOptions() {
		return [
			self::CZECH_LANGUAGE => Module::t('inst', 'czech'),
			self::ENGLISH_LANGUAGE => Module::t('inst', 'english'),
			self::GERMAN_LANGUAGE => Module::t('inst', 'german')
		];
	}
}