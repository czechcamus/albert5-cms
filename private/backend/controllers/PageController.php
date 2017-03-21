<?php

namespace backend\controllers;

use backend\models\PageForm;
use backend\utilities\ContentController;
use common\models\ContentFieldRecord;
use Yii;
use backend\models\PageSearch;

/**
 * PageController implements the CRUD actions for Page model.
 */
class PageController extends ContentController
{
    /**
     * Lists all Page models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PageSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', compact('searchModel', 'dataProvider'));
    }

    /**
     * Creates a new Page model.
     * If creation is successful, the browser will be redirected to the 'index' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new PageForm();
	    $model->scenario = 'create';

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
	        $model->savePage();

		    $additionalFieldIds = Yii::$app->request->post('AdditionalFieldId');
		    if ($additionalFieldIds) {
			    foreach ( $additionalFieldIds as $key => $content ) {
				    $contentField = new ContentFieldRecord();
				    $contentField->content_id = $model->item_id;
				    $contentField->additional_field_id = $key;
				    $contentField->content = $content;
				    $contentField->save();
			    }
		    }

	        $session = Yii::$app->session;
	        $session->setFlash('info', Yii::t('back', 'New page successfully added!'));

	        return $this->redirect(['index']);
        } else {
            return $this->render('create', compact('model'));
        }
    }

    /**
     * Creates a new Page model from an existing model.
     * If creation is successful, the browser will be redirected to the 'index' page.
     * @param $id
     * @return mixed
     */
    public function actionCopy($id)
    {
        $model = new PageForm($id);
        $model->scenario = 'create';

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $model->savePage();

	        $additionalFieldIds = Yii::$app->request->post('AdditionalFieldId');
	        if ($additionalFieldIds) {
		        foreach ( $additionalFieldIds as $key => $content ) {
			        $contentField = new ContentFieldRecord();
			        $contentField->content_id = $model->item_id;
			        $contentField->additional_field_id = $key;
			        $contentField->content = $content;
			        $contentField->save();
		        }
	        }

            $session = Yii::$app->session;
            $session->setFlash('info', Yii::t('back', 'New page successfully added!'));

            return $this->redirect(['index']);
        } else {
            return $this->render('create', compact('model'));
        }
    }

    /**
     * Updates an existing Page model.
     * If update is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = new PageForm($id);
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
	        $model->savePage(false);

	        $additionalFieldIds = Yii::$app->request->post('AdditionalFieldId');
	        $contentFields = ContentFieldRecord::findAll(['content_id' => $model->item_id]);
	        foreach ( $contentFields as $contentField ) {
		        if (isset($additionalFieldIds[$contentField->additional_field_id])) {
			        $contentField->content = $additionalFieldIds[$contentField->additional_field_id];
			        $contentField->save();
			        unset($additionalFieldIds[$contentField->additional_field_id]);
		        } else {
			        $contentField->delete();
		        }
	        }
	        if ($additionalFieldIds) {
		        foreach ( $additionalFieldIds as $key => $content ) {
			        $contentField = new ContentFieldRecord;
			        $contentField->content_id = $model->item_id;
			        $contentField->additional_field_id = $key;
			        $contentField->content = $content;
			        $contentField->save();
		        }
	        }

	        $session = Yii::$app->session;
	        $session->setFlash('info', Yii::t('back', 'Page successfully updated!'));

	        return $this->redirect(['index']);
        } else {
            return $this->render('update', compact('model'));
        }
    }

    /**
     * Deletes an existing Page model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
	    $model = new PageForm($id);
	    $model->deletePage();

	    $session = Yii::$app->session;
	    $session->setFlash('info', Yii::t('back', 'Page successfully deleted!'));

	    return $this->redirect(['index']);
    }
}
