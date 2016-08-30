<?php

namespace backend\controllers;

use backend\models\AdditionalFieldSearch;
use backend\utilities\BackendController;
use common\models\AdditionalFieldRecord;
use common\models\LanguageRecord;
use Yii;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * AdditionalFieldController implements the CRUD actions for Additional field model.
 */
class AdditionalFieldController extends BackendController
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
     * Lists all AdditionalField record models.
     * @return mixed
     */
    public function actionIndex()
    {
	    $searchModel = new AdditionalFieldSearch;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', compact('dataProvider'));
    }

    /**
     * Creates a new AdditionalField record model.
     * If creation is successful, the browser will be redirected to the 'index' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new AdditionalFieldRecord();

        if ($model->load(Yii::$app->request->post())) {
            $session = Yii::$app->session;
	        if (!$session['language_id'])
		        $session['language_id'] = LanguageRecord::getMainLanguageId();
	        $model->language_id = $session['language_id'];
            if ($model->save()) {
	            $session->setFlash('info', Yii::t('back', 'New additional field successfully added!'));
	            return $this->redirect( 'index' );
	        }
        } elseif (Yii::$app->request->isAjax) {
	        return $this->renderAjax('_form', compact('model'));
        }

	    return $this->render('_form', compact('model'));
    }

    /**
     * Updates an existing AdditionalField record model.
     * If update is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
	        $session = Yii::$app->session;
	        $session->setFlash('info', Yii::t('back', 'Additional field successfully updated!'));

	        return $this->redirect( 'index' );
        } elseif (Yii::$app->request->isAjax) {
	        return $this->renderAjax('_form', compact('model'));
        }

	    return $this->render('_form', compact('model'));
    }

    /**
     * Deletes an existing AdditionalField record model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

	    $session = Yii::$app->session;
	    $session->setFlash('info', Yii::t('back', 'Additional field successfully deleted!'));

        return $this->redirect(['index']);
    }

    /**
     * Finds the AdditionalField record model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return AdditionalFieldRecord the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = AdditionalFieldRecord::findOne($id)) !== null)
            return $model;

        throw new NotFoundHttpException(Yii::t('back', 'The requested page does not exist.'));
    }
}
