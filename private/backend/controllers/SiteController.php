<?php
namespace backend\controllers;

use backend\utilities\BackendController;
use Yii;
use yii\filters\AccessControl;
use yii\helpers\Url;
use common\models\LoginForm;
use yii\filters\VerbFilter;

/**
 * Site controller
 */
class SiteController extends BackendController
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['login', 'error', 'offline'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout', 'index', 'switch-language'],
                        'allow' => true,
                        'roles' => ['@'],
                    ]
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
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

    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionLogin()
    {
        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

	public function actionOffline()
	{
		return $this->render('offline');
	}

	/**
	 * @param integer $id  language id
	 *
	 * @return \yii\web\Response
	 */
	public function actionSwitchLanguage($id)
	{
		$session = Yii::$app->session;
		$session['language_id'] = $id;
		return $this->redirect(Url::previous());
	}
}
