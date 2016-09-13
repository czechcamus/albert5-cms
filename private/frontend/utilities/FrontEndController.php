<?php
/**
 * Created by PhpStorm.
 * User: Pavel
 * Date: 1.10.2015
 * Time: 10:31
 */

namespace frontend\utilities;


use common\models\LanguageRecord;
use common\models\WebRecord;
use Yii;
use yii\base\Theme;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class FrontEndController extends Controller
{
	/** @var WebRecord */
	public $web;

	/** @var LanguageRecord */
	public $language;

	/**
	 * @inheritdoc
	 */
	public function init() {
		parent::init();

		$session = Yii::$app->session;
		if (WebRecord::existsMoreWebRecords()) {
			if (!$session->has('web')) {
				$session->set('web', \Yii::$app->request->get( 'web', \Yii::$app->params['defaultWeb'] ));
			}
			if (!$this->web = WebRecord::findOne( FrontEndHelper::getWebIdFromTextId( $session->get('web') ) )) {
				$session->set('web', \Yii::$app->params['defaultWeb']);
				throw new NotFoundHttpException(Yii::t('front', 'The requested page does not exist.'));
			}
		} else {
			$this->redirect(['install/default/index']);
		}
		if (!$this->web) {
			$this->web = WebRecord::findOne( FrontEndHelper::getWebIdFromTextId( $session->get('web') ));
		}
		$session->set('web', null);

		$this->setWebTheme($this->web->theme);

		\Yii::$app->language = \Yii::$app->request->get('language', \Yii::$app->params['defaultLanguage']);

		$this->language = LanguageRecord::findOne(FrontEndHelper::getLanguageIdFromAcronym());
	}

	/**
	 * Sets view theme of the web
	 * @param string $theme
	 */
	private function setWebTheme( $theme = 'basic' ) {
		\Yii::$app->view->theme = new Theme([
			'pathMap' => ['@app/views' => '@app/themes/' . $theme],
			'basePath' => '@app/themes/' . $theme,
			'baseUrl' => '@web'
		]);
	}

}