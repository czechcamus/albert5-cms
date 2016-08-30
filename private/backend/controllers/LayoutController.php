<?php

namespace backend\controllers;

use backend\utilities\BackendController;
use Yii;
use common\models\LayoutRecord;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * LayoutController implements the CRUD actions for Layout record model.
 */
class LayoutController extends BackendController
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
     * Lists all Layout record models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => LayoutRecord::find(),
        ]);

        return $this->render('index', compact('dataProvider'));
    }

    /**
     * Creates a new Layout record model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new LayoutRecord();
	    $model->initBoxes();

        if ($model->load(Yii::$app->request->post())) {
	        $model->setBoxesProperties();
	        if($model->save()) {
		        $session = Yii::$app->session;
		        $session->setFlash('info', Yii::t('back', 'New layout successfully added!'));

		        return $this->redirect( [ 'index' ] );
	        }
        } elseif (Yii::$app->request->isAjax) {
	        return $this->renderAjax('_form', compact('model'));
        }

	    return $this->render('_form', compact('model'));
    }

    /**
     * Updates an existing Layout record model.
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
		        $session->setFlash('info', Yii::t('back', 'Layout successfully updated!'));

		        return $this->redirect( [ 'index' ] );
	        }
        } elseif (Yii::$app->request->isAjax) {
	        return $this->renderAjax('_form', compact('model'));
        }

	    return $this->render('_form', compact('model'));
    }

    /**
     * Deletes an existing Layout record model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

	    $session = Yii::$app->session;
	    $session->setFlash('info', Yii::t('back', 'Layout successfully deleted!'));

        return $this->redirect(['index']);
    }

    /**
     * Finds the Layout model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return LayoutRecord the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = LayoutRecord::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
