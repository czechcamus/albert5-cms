<?php

namespace backend\controllers;

use backend\utilities\BackendController;
use Yii;
use common\models\WebRecord;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * WebController implements the CRUD actions for Web record model.
 */
class WebController extends BackendController
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
     * Lists all Web record models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => WebRecord::find(),
        ]);

        return $this->render('index', compact('dataProvider'));
    }

    /**
     * Creates a new Web record model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new WebRecord();
	    $model->initBoxes();

        if ($model->load(Yii::$app->request->post())) {
	        $model->setBoxesProperties();
            if ($model->save()) {
	            $session = Yii::$app->session;
	            $session->setFlash('info', Yii::t('back', 'New web successfully added!'));

				return $this->redirect(['index']);
            }
        } elseif (Yii::$app->request->isAjax) {
	        return $this->renderAjax('_form', compact('model'));
        }

        return $this->render('_form', compact('model'));
    }

    /**
     * Updates an existing Web record model.
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
		        $session->setFlash('info', Yii::t('back', 'Web successfully updated!'));

		        return $this->redirect(['index']);
	        }
        } elseif (Yii::$app->request->isAjax) {
	        return $this->renderAjax('_form', compact('model'));
        }

	    return $this->render('_form', compact('model'));
    }

    /**
     * Deletes an existing Web record model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
	    $this->findModel($id)->delete();

	    $session = Yii::$app->session;
	    $session->setFlash('info', Yii::t('back', 'Web successfully deleted!'));

	    return $this->redirect(['index']);
    }

    /**
     * Finds the Web model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return WebRecord the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = WebRecord::findOne($id)) !== null)
            return $model;

        throw new NotFoundHttpException(Yii::t('back', 'The requested page does not exist.'));
    }
}
