<?php

namespace backend\controllers;

use backend\models\PageForm;
use backend\utilities\BackendController;
use backend\utilities\SynchronizeFiles;
use common\models\PageFieldRecord;
use Yii;
use backend\models\PageSearch;
use yii\db\Query;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\Html;

/**
 * PageController implements the CRUD actions for Page model.
 */
class PageController extends BackendController
{
	/**
	 * @inheritdoc$
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
			            'roles' => ['user'],
			            'allow' => true
		            ]
	            ]
            ],
            'synchronize'   => [
                'class' => SynchronizeFiles::className(),
                'only'  => [ 'create', 'copy', 'update' ]
            ]
        ];
    }

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
				    $pageField = new PageFieldRecord();
				    $pageField->page_id = $model->item_id;
				    $pageField->additional_field_id = $key;
				    $pageField->content = $content;
				    $pageField->save();
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
			        $pageField = new PageFieldRecord();
			        $pageField->page_id = $model->item_id;
			        $pageField->additional_field_id = $key;
			        $pageField->content = $content;
			        $pageField->save();
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
	        $pageFields = PageFieldRecord::findAll(['page_id' => $model->item_id]);
	        foreach ( $pageFields as $pageField ) {
		        if (isset($additionalFieldIds[$pageField->additional_field_id])) {
			        $pageField->content = $additionalFieldIds[$pageField->additional_field_id];
			        $pageField->save();
			        unset($additionalFieldIds[$pageField->additional_field_id]);
		        } else {
			        $pageField->delete();
		        }
	        }
	        if ($additionalFieldIds) {
		        foreach ( $additionalFieldIds as $key => $content ) {
			        $pageField = new PageFieldRecord();
			        $pageField->page_id = $model->item_id;
			        $pageField->additional_field_id = $key;
			        $pageField->content = $content;
			        $pageField->save();
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

	/**
	 * Adds selection for additional fields to page form
	 * @return string
	 */
	public function actionShowAdditionalFieldForm() {
		$availableFieldsArray = Yii::$app->request->get('available_fields');
		$output = '';
		$output .= '<div class="form-group">';
		$output .= '<label for="additional-field-record_id">' . Yii::t('back', 'Select additional field to add') . '</label>';
		$output .= '<select name="AdditionalFieldRecord[id]" id="additional-field-record_id" class="form-control">';
		foreach ( $availableFieldsArray as $item ) {
			$label = (new Query())->select('label')->from('additional_field')->where(['id' => $item])->scalar();
			$output .= '<option value="' . $item . '">' . $label . '</option>';
		}
		$output .= '</select></div>';
		$output .= Html::a(Yii::t('back','add'), '#!', [
			'id' => 'save-field-btn',
			'class' => 'btn btn-success'
		]);
		$output .= ' ' . Html::a(Yii::t('back','cancel'), '#!', [
			'id' => 'cancel-field-btn',
			'class' => 'btn btn-default'
		]);
		return $output;
	}

	/**
	 * Displays additional field
	 * @return string
	 */
	public function actionShowAdditionalField() {
		$output = '';
		$additionalFieldId = Yii::$app->request->get('additional_field_id');
		$additionalFieldLabel = (new Query())->select('label')->from('additional_field')->where(['id' => $additionalFieldId])->scalar();
		$output .= '<div class="form-inline"><div class="form-group">';
		$output .= '<label for="AdditionalFieldId[' . $additionalFieldId . ']">' . $additionalFieldLabel .'</label><br />';
		$output .= Html::textInput('AdditionalFieldId[' . $additionalFieldId . ']', null, ['class' => 'form-control']);
		$output .= ' ' . Html::a('<span class="glyphicon glyphicon-remove"></span>', '#!', [
			'class' => 'form-control btn btn-danger remove-field-btn',
			'data-field-id' => $additionalFieldId
		]);
		$output .= '</div></div>';
		return $output;
	}
}
