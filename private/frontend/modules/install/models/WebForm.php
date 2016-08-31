<?php
/**
 * Created by PhpStorm.
 * User: Pavel
 * Date: 30.8.2016
 * Time: 15:04
 */

namespace frontend\modules\install\models;


use common\models\LanguageRecord;
use common\models\WebRecord;
use frontend\modules\install\Module;

class WebForm extends InstallForm {
	// web
	public $webTitle;
	public $webOwner;
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

	// emails
	public $adminEmail;
	public $supportEmail;

	// newsletter
	public $sendingEmail;
	public $sendingEmailTitle;

	// others
	public $cmsWebTitle = 'Web application';
	public $language = 'cs';

	const ENGLISH_LANGUAGE = 'en';
	const CZECH_LANGUAGE = 'cs';
	const GERMAN_LANGUAGE = 'de';

	/**
	 * @inheritdoc
	 */
	public function rules() {
		return [
			[
				[
					'webTitle',
					'languageAcronym',
					'webOwner',
					'adminEmail',
					'supportEmail',
					'sendingEmail',
					'sendingEmailTitle'
				],
				'required'
			],
			[ [ 'adminEmail', 'supportEmail', 'sendingEmail' ], 'email' ]
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels() {
		return [
			'webTitle'          => Module::t( 'inst', 'Name of main web' ),
			'webOwner'          => Module::t( 'inst', 'Owner of web' ),
			'languageAcronym'   => Module::t( 'inst', 'Main language (frontend and backend)' ),
			'adminEmail'        => Module::t( 'inst', 'Email for contact form' ),
			'supportEmail'      => Module::t( 'inst', 'Email of webmaster' ),
			'sendingEmail'      => Module::t( 'inst', 'Email of newsletter sender' ),
			'sendingEmailTitle' => Module::t( 'inst', 'Title of newsletter email (subject)' ),
		];
	}

	/**
	 * Language options for dropdowns
	 * @return array
	 */
	public function getLanguageOptions() {
		return [
			self::CZECH_LANGUAGE   => Module::t( 'inst', 'czech' ),
			self::ENGLISH_LANGUAGE => Module::t( 'inst', 'english' ),
			self::GERMAN_LANGUAGE  => Module::t( 'inst', 'german' )
		];
	}

	public function save() {
		@$this->saveWebRecord();
		@$this->saveLanguageRecord();
		$this->setConfig( \Yii::getAlias( '@common' ) . '/config/params.php', [
			'webOwner',
			'adminEmail',
			'supportEmail',
			'sendingEmail',
			'sendingEmailTitle'
		] );
		$this->cmsWebTitle = $this->webTitle;
		$this->setConfig( \Yii::getAlias( '@backend' ) . '/config/params.php', [
			'cmsWebTitle'
		] );
		$this->language = $this->languageAcronym;
		$this->setConfig( \Yii::getAlias( '@backend' ) . '/config/main.php', [
			'language'
		] );
	}

	/**
	 * @return boolean
	 */
	private function saveWebRecord() {
		if ( ! $items = WebRecord::find()->count() ) {
			$model         = new WebRecord;
			$model->title  = $this->webTitle;
			$model->weburl = $this->webUrl;
			$model->active = $this->webActive;
			$model->main   = $this->webMain;
			$model->public = $this->webPublic;
			$model->theme  = $this->webTheme;
			$model->save( false );
		}
		return $items ? false : true;
	}

	/**
	 * @return int language id
	 */
	private function saveLanguageRecord() {
		if ( ! $items = LanguageRecord::find()->count() ) {
			$model          = new LanguageRecord;
			$model->title   = $this->getLanguageOptions()[ $this->languageAcronym ];
			$model->acronym = $this->languageAcronym;
			$model->main    = $this->languageMain;
			$model->active  = $this->languageActive;
			$model->save( false );
		}
		return $items ? false : true;
	}
}