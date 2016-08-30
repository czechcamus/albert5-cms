<?php

namespace backend\controllers;

use backend\models\UserForm;
use backend\utilities\BackendController;
use Yii;
use common\models\UserRecord;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * UserController implements the CRUD actions for UserForm model.
 */
class UserController extends BackendController
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'roles' => ['admin'],
                        'allow' => true
                    ]
                ]
            ]
        ];
    }

    /**
     * Lists all User record models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => UserRecord::find()->where(['not', ['status' => UserRecord::STATUS_DELETED]])
        ]);

        return $this->render('index', compact('dataProvider'));
    }

    /**
     * Creates a new User and assign his role.
     * If creation is successful, the browser will be redirected to the 'index' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new UserForm(['scenario' => 'create']);

        if ($model->load(Yii::$app->request->post()) && $model->saveUser()) {
	        $session = Yii::$app->session;
	        $session->setFlash('info', Yii::t('back', 'New user successfully added!'));
	        return $this->redirect( [ 'index' ] );
        } elseif (Yii::$app->request->isAjax) {
	        return $this->renderAjax('_form', compact('model'));
        }

	    return $this->render('_form', compact('model'));
    }

    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = new UserForm();

        if ($model->load(Yii::$app->request->post()) && $model->saveUser($id)) {
	        $session = Yii::$app->session;
	        $session->setFlash('info', Yii::t('back', 'User successfully updated!'));

            return $this->redirect(['index']);

        } else {
	        $user = $this->findModel($id);
	        $model->username = $user->username;
	        $model->email = $user->email;
	        $model->password = '';
	        $auth = Yii::$app->authManager;
	        $roles = array_keys($auth->getRolesByUser($id));
	        $model->role = count($roles) ? $roles[0] : '';
        }

		if (Yii::$app->request->isAjax) {
	        return $this->renderAjax('_form', compact('model'));
        }

        return $this->render('_form', compact('model'));
    }

    /**
     * Deletes an existing User model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = new UserForm();
	    $session = Yii::$app->session;

        if ($model->deleteUser($id)) {
		    $session->setFlash('info', Yii::t('back', 'User successfully deleted!'));
        } else {
	        $session->setFlash('info', Yii::t('back', 'Something went wrong  - user not deleted!'));
        }
        return $this->redirect(['index']);
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return UserRecord the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = UserRecord::findOne($id)) !== null)
            return $model;

        throw new NotFoundHttpException(Yii::t('back', 'The requested page does not exist.'));
    }
}
