<?php
/**
 * Created by PhpStorm.
 * User: Pavel
 * Date: 30.8.2016
 * Time: 7:08
 */

namespace frontend\modules\install;


use Yii;

class Module extends \yii\base\Module
{
	public function init() {
		parent::init();
		\Yii::configure($this, require(__DIR__ . '/config/config.php'));
		$this->registerTranslations();
	}

	public function registerTranslations()
	{
		Yii::$app->i18n->translations['modules/install/inst'] = [
			'class' => 'yii\i18n\PhpMessageSource',
			'basePath' => '@frontend/modules/install/messages',
			'fileMap' => [
				'modules/users/inst' => 'inst.php'
            ],
        ];
    }


	public static function t($category, $message, $params = [], $language = null)
	{
		return Yii::t('modules/install/' . $category, $message, $params, $language);
	}
}