<?php

namespace backend\controllers;

use backend\utilities\BackendController;
use Yii;
use common\models\LanguageRecord;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * LanguageController implements the CRUD actions for Language model.
 */
class LanguageController extends BackendController
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
     * Lists all Language record models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => LanguageRecord::find(),
        ]);

        return $this->render('index', compact('dataProvider'));
    }

    /**
     * Creates a new Language record model.
     * If creation is successful, the browser will be redirected to the 'index' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new LanguageRecord();
	    $model->initBoxes();

        if ($model->load(Yii::$app->request->post())) {
	        $model->setBoxesProperties();
            if ( $model->save() ) {
	            $session = Yii::$app->session;
	            $session->setFlash('info', Yii::t('back', 'New language successfully added!'));

	            return $this->redirect( 'index' );
            }
        } elseif (Yii::$app->request->isAjax) {
	        return $this->renderAjax('_form', compact('model'));
        }

	    return $this->render('_form', compact('model'));
    }

    /**
     * Updates an existing Language record model.
     * If update is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
	    $model->initBoxes(false);

        if ($model->load(Yii::$app->request->post())) {
	        $model->setBoxesProperties();
	        if ( $model->save() ) {
		        $session = Yii::$app->session;
		        $session->setFlash('info', Yii::t('back', 'Language successfully updated!'));

		        return $this->redirect( 'index' );
	        }
        } elseif (Yii::$app->request->isAjax) {
	        return $this->renderAjax('_form', compact('model'));
        }

	    return $this->render('_form', compact('model'));
    }

    /**
     * Deletes an existing Language record model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

	    $session = Yii::$app->session;
	    $session->setFlash('info', Yii::t('back', 'Language successfully deleted!'));

        return $this->redirect(['index']);
    }

    /**
     * Finds the Language record model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return LanguageRecord the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = LanguageRecord::findOne($id)) !== null)
            return $model;

        throw new NotFoundHttpException(Yii::t('back', 'The requested page does not exist.'));
    }
}
