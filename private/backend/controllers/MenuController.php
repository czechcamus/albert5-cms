<?php

namespace backend\controllers;

use backend\models\MenuSearch;
use backend\utilities\BackendController;
use backend\utilities\WebFilter;
use common\models\WebRecord;
use Yii;
use common\models\MenuRecord;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * MenuController implements the CRUD actions for Menu record model.
 */
class MenuController extends BackendController
{
	private $_web;

    /**
     * @inheritdoc
     * @return array
     */
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
            ],
            'web' => WebFilter::className()
        ];
    }

    /**
     * Lists all Menu record models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new MenuSearch;
        $dataProvider = $searchModel->search($this->_web->id);
	    $web_id = $this->_web->id;

        return $this->render('index', compact('dataProvider', 'web_id'));
    }

    /**
     * Creates a new Menu record model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new MenuRecord();
	    $model->web_id = $this->_web->id;
        $model->initBoxes();

        if ($model->load(Yii::$app->request->post())) {
	        $model->setBoxesProperties();
	        if ($model->save()) {
		        $session = Yii::$app->session;
		        $session->setFlash('info', Yii::t('back', 'Menu successfully added!'));

	            return $this->redirect(['index']);
	        }
		} elseif (Yii::$app->request->isAjax) {
			return $this->renderAjax('_form', compact('model'));
		}

	    return $this->render('_form', compact('model'));
    }

    /**
     * Updates an existing Menu record model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
	    $model->initBoxes(false);

        if ($model->load(Yii::$app->request->post())) {
	        $model->setBoxesProperties();
	        if ($model->save()) {
		        $session = Yii::$app->session;
		        $session->setFlash('info', Yii::t('back', 'Menu successfully updated!'));

	            return $this->redirect(['index']);
	        }
        } elseif (Yii::$app->request->isAjax) {
	        return $this->renderAjax('_form', compact('model'));
        }

	    return $this->render('_form', compact('model'));
    }

    /**
     * Deletes an existing Menu record model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

	    $session = Yii::$app->session;
	    $session->setFlash('info', Yii::t('back', 'Menu successfully deleted!'));

	    return $this->redirect(['index']);
    }

    /**
     * Finds the Menu model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return MenuRecord the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = MenuRecord::findOne($id)) !== null)
            return $model;

        throw new NotFoundHttpException(Yii::t('back', 'The requested page does not exist.'));
    }

	/**
	 * Sets $_web property
	 * @param integer $id
	 *
	 * @throws NotFoundHttpException
	 */
	public function setWeb( $id ) {
		if (($this->_web = WebRecord::findOne($id)) === null)
			throw new NotFoundHttpException(Yii::t('back', 'The requested web does not exist.'));

	}

	/**
	 * Gets $_web property
	 * @return WebRecord mixed
	 */
	public function getWeb() {
		return $this->_web;
	}
}
