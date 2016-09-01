<?php
/**
 * Created by PhpStorm.
 * User: Pavel
 * Date: 30.8.2016
 * Time: 15:04
 */

namespace frontend\modules\install\models;


use common\models\ContentRecord;
use common\models\LanguageRecord;
use common\models\LayoutRecord;
use common\models\MenuItemContent;
use common\models\MenuItemRecord;
use common\models\MenuRecord;
use common\models\Page;
use common\models\WebRecord;
use frontend\modules\install\Module;
use yii\base\Exception;

class WebForm extends InstallForm {
	// web
	public $webTitle;
	public $webOwner;

	// language
	public $languageAcronym;

	// emails
	public $adminEmail;
	public $supportEmail;

	// newsletter
	public $sendingEmail;
	public $sendingEmailTitle;

	// others
	public $active = 1;
	public $main = 1;
	public $public = 1;
	public $cmsWebTitle = 'Backend web application';
	public $name = 'Frontend web application';
	public $language = 'cs';
	public $defaultLanguage = 'cs';

	const ENGLISH_LANGUAGE = 'en';
	const CZECH_LANGUAGE = 'cs';

	// private properties
	private $_webId;
	private $_languageId;
	private $_menuId;
	private $_homepageContentId;
	private $_homepageTemplateId;

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
			self::ENGLISH_LANGUAGE => Module::t( 'inst', 'english' )
		];
	}

	public function save() {
		if ($this->_webId = $this->saveWebRecord()) {
			if ($this->_languageId = $this->saveLanguageRecord()) {
				if ($this->_menuId = $this->saveMenuRecord()) {
					if ($this->_homepageContentId = $this->saveHomepageContent()) {
						if ($this->_homepageTemplateId = $this->saveBasicTemplates()) {
							if ($this->saveMenuItemRecord()) {
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
								$this->defaultLanguage = $this->languageAcronym;
								$this->setConfig( \Yii::getAlias( '@frontend' ) . '/config/params.php', [
									'defaultLanguage'
								] );
								$this->name = $this->webTitle;
								$this->setConfig( \Yii::getAlias( '@frontend' ) . '/config/main.php', [
									'name'
								] );
							} else {
								throw new Exception(Module::t('inst', 'Main menu item record not available!'));
							}
						} else {
							throw new Exception(Module::t('inst', 'Layout\'s don\'t exist!'));
						}
					} else {
						throw new Exception(Module::t('inst', 'Homepage record not available!'));
					}
				} else {
					throw new Exception(Module::t('inst', 'Main menu record not available!'));
				}
			} else {
				throw new Exception(Module::t('inst', 'Main language record not available!'));
			}
		} else {
			throw new Exception(Module::t('inst', 'Main web record not available!'));
		}
	}

	/**
	 * @return int web id
	 */
	private function saveWebRecord() {
		/** @var $item  WebRecord*/
		if ( ! $item = WebRecord::find()->one() ) {
			$model         = new WebRecord;
			$model->title  = $this->webTitle;
			$model->weburl = 'main';
			$model->active = $this->active;
			$model->main   = $this->main;
			$model->public = $this->public;
			$model->theme  = 'basic';
			$model->save( false );
			return $model->id;
		} else {
			return $item->id;
		}
	}

	/**
	 * @return int language id
	 */
	private function saveLanguageRecord() {
		/** @var $item LanguageRecord */
		if ( ! $item = LanguageRecord::find()->one() ) {
			$model          = new LanguageRecord;
			$model->title   = $this->getLanguageOptions()[ $this->languageAcronym ];
			$model->acronym = $this->languageAcronym;
			$model->main    = $this->main;
			$model->active  = $this->active;
			$model->save( false );
			return $model->id;
		} else {
			return $item->id;
		}
	}

	/**
	 * @return int menu id
	 */
	private function saveMenuRecord() {
		/** @var $item MenuRecord */
		if (!$item = MenuRecord::find()->one()) {
			$model = new MenuRecord;
			$model->title = Module::t('inst', 'main menu');
			$model->text_id = 'mainmenu';
			$model->web_id = $this->_webId;
			$model->active = $this->active;
			$model->main = $this->main;
			$model->public = $this->public;
			$model->save(false);
			return $model->id;
		} else {
			return $item->id;
		}
	}

	/**
	 * @return int page content id
	 */
	private function saveHomepageContent() {
		/** @var $item ContentRecord */
		if (!$item = ContentRecord::find()->one()) {
			$model = new Page;
			$model->language_id = $this->_languageId;
			$model->title = Module::t('inst', 'Home page');
			$model->perex = Module::t('inst', 'Some highlight text.');
			$model->description = Module::t('inst', 'Content of home page.');
			$model->active = $this->active;
			$model->public = $this->public;
			$model->save(false);
			return $model->id;
		} else {
			return $item->id;
		}
	}

	/**
	 * @return bool|int homepage id
	 */
	private function saveBasicTemplates() {
		$homepageId = 0;
		$basicTemplates = [
			[ 'title' => Module::t('inst', 'Default article'), 'filename' => 'article', 'main' => 1, 'content' => LayoutRecord::CONTENT_ARTICLE ],
			[ 'title' => Module::t('inst', 'Article with main image'), 'filename' => 'article-image', 'main' => 0, 'content' => LayoutRecord::CONTENT_ARTICLE ],
			[ 'title' => Module::t('inst', 'Default category'), 'filename' => 'category', 'main' => 1, 'content' => LayoutRecord::CONTENT_CATEGORY ],
			[ 'title' => Module::t('inst', 'Category with pagination'), 'filename' => 'category-agregate', 'main' => 0, 'content' => LayoutRecord::CONTENT_CATEGORY ],
			[ 'title' => Module::t('inst', 'Homepage'), 'filename' => 'homepage', 'main' => 0, 'content' => LayoutRecord::CONTENT_PAGE ],
			[ 'title' => Module::t('inst', 'Invitations'), 'filename' => 'invitations', 'main' => 0, 'content' => LayoutRecord::CONTENT_CATEGORY ],
			[ 'title' => Module::t('inst', 'Invitations archive'), 'filename' => 'invitations-archive', 'main' => 0, 'content' => LayoutRecord::CONTENT_CATEGORY ],
			[ 'title' => Module::t('inst', 'Default page'), 'filename' => 'page', 'main' => 1, 'content' => LayoutRecord::CONTENT_PAGE ],
		];
		foreach ( $basicTemplates as $basicTemplate ) {
			$model = new LayoutRecord;
			$model->attributes = $basicTemplate;
			$model->active = $this->active;
			if ($model->save(false)) {
				if ($model->filename === 'homepage') {
					$homepageId = $model->id;
				}
			} else {
				return false;
			}
		}
		return $homepageId;
	}

	/**
	 * @return bool
	 */
	private function saveMenuItemRecord() {
		if (!$item = MenuItemRecord::find()->one()) {
			$model = new MenuItemRecord;
			$model->title = Module::t('inst', 'home');
			$model->language_id = $this->_languageId;
			$model->menu_id = $this->_menuId;
			$model->layout_id = $this->_homepageTemplateId;
			$model->active = $this->active;
			$model->main = $this->main;
			$model->public = $this->public;
			$model->content_type = MenuItemRecord::CONTENT_PAGE;
			if ($model->save(false)) {
				$junctionModel = new MenuItemContent;
				$junctionModel->menu_item_id = $model->id;
				$junctionModel->content_id = $this->_homepageContentId;
				return $junctionModel->save(false);
			}
		}
		return true;
	}
}