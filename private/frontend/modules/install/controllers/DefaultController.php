<?php
/**
 * Created by PhpStorm.
 * User: Pavel
 * Date: 30.8.2016
 * Time: 7:12
 */

namespace frontend\modules\install\controllers;


use common\models\WebRecord;
use frontend\modules\install\models\SignupForm;
use frontend\modules\install\models\WebForm;
use frontend\modules\install\Module;
use yii\base\Exception;
use yii\filters\AccessControl;
use yii\web\Controller;

class DefaultController extends Controller
{
	/**
	 * @inheritdoc
	 */
	public function behaviors() {
		return [
			'access' => [
				'class' => AccessControl::className(),
				'rules' => [
					[
						'actions' =>['index', 'error'],
						'allow' => true
					],
					[
						'actions' =>['web'],
						'allow' => true,
						'roles' => ['@']
					]
				]
			]
		];
	}

	/**
	 * @inheritdoc
	 */
	public function actions()
	{
		return [
			'error' => [
				'class' => 'yii\web\ErrorAction',
			],
		];
	}

	public function actionIndex() {
		if (WebRecord::existsMoreWebRecords()) {
			throw new Exception(Module::t('inst','CMS is already installed!'));
		} else {
			$session = \Yii::$app->session;
			$model = new SignupForm;
			if ($model->load(\Yii::$app->request->post()) && $model->validate()) {
				if ($user = $model->signup()) {
					if (\Yii::$app->getUser()->login($user)) {
						$session->set('step', 1);
						return $this->redirect(['web']);
					}
				}
			}
			return $this->render('index', compact('model'));
		}
	}

	public function actionWeb() {
		$session = \Yii::$app->session;
		if (!$session->has('step')) {
			throw new Exception(Module::t('inst', 'This action is not allowed!'));
		} else {
			$model = new WebForm;
			if ($model->load(\Yii::$app->request->post()) && $model->validate()) {
				$model->save();
				$session->set('step', 2);
				return $this->redirect(['frontend']);
			}
			return $this->render('web', compact('model'));
		}
	}

	public function actionFrontend() {
		$session = \Yii::$app->session;
		if ($session->get('step') < 2) {
			throw new Exception(Module::t('inst', 'This action is not allowed!'));
		} else {
			return $this->render('frontend');
		}
	}
}