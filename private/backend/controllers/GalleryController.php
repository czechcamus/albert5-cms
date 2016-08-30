<?php

namespace backend\controllers;

use backend\models\GalleryAddPhotosForm;
use backend\models\GalleryForm;
use backend\models\GalleryPhotosForm;
use backend\models\GallerySearch;
use backend\utilities\BackendController;
use backend\utilities\SynchronizeFiles;
use common\models\Gallery;
use common\models\ImageGallery;
use common\models\LanguageRecord;
use Yii;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\filters\VerbFilter;
use yii\web\Response;

/**
 * GalleryController implements the CRUD actions for CategoryRecord model.
 */
class GalleryController extends BackendController
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
						'roles' => ['user'],
						'allow' => true
					]
				]
			],
			'synchronize'   => [
				'class' => SynchronizeFiles::className(),
				'only'  => [ 'photos' ]
			]
		];
	}

	/**
	 * Lists all Gallery models.
	 * @return mixed
	 */
	public function actionIndex()
	{
		$searchModel = new GallerySearch;
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams);

		return $this->render('index', compact('searchModel', 'dataProvider'));
	}

	/**
	 * Creates a new GalleryForm model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 * @return mixed
	 */
	public function actionCreate()
	{
		$model = new GalleryForm;
		$model->scenario = 'create';

		if ($model->load(Yii::$app->request->post()) && $model->validate()) {
			$model->saveGallery();

			$session = Yii::$app->session;
			$session->setFlash('info', Yii::t('back', 'New gallery successfully added!'));

			return $this->redirect(['index']);
		} elseif (Yii::$app->request->isAjax) {
			return $this->renderAjax('_form', compact('model'));
		}

		return $this->render('_form', compact('model'));
	}

	/**
	 * Updates an existing GalleryForm model.
	 * If update is successful, the browser will be redirected to the 'index' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionUpdate($id)
	{
		$model = new GalleryForm($id);
		$model->scenario = 'update';

		if ($model->load(Yii::$app->request->post()) && $model->validate()) {
			$model->saveGallery(false);

			$session = Yii::$app->session;
			$session->setFlash('info', Yii::t('back', 'Gallery successfully updated!'));

			return $this->redirect(['index']);
		} elseif (Yii::$app->request->isAjax) {
			return $this->renderAjax('_form', compact('model'));
		}

		return $this->render('_form', compact('model'));
	}

	/**
	 * Deletes an existing Gallery model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionDelete($id)
	{
		$model = new GalleryForm($id);
		$model->deleteGallery();

		$session = Yii::$app->session;
		$session->setFlash('info', Yii::t('back', 'Gallery successfully deleted!'));

		return $this->redirect(['index']);
	}

	/**
	 * Displays gallery photos and those manage options
	 * @param $id
	 * @return string
	 */
	public function actionPhotos($id) {
		$model = new GalleryPhotosForm($id);

		if ($model->load(Yii::$app->request->post())) {
			foreach ( $item_order = explode(',', $model->item_order) as $key => $value ) {
				/** @var ImageGallery $imageGallery */
				$imageGallery = ImageGallery::findOne($value);
				$imageGallery->item_order = $key + 1;
				$imageGallery->update(false, ['item_order']);
			}
			$session = Yii::$app->session;
			$session->setFlash('info', Yii::t('back', 'Photos order successfully saved!'));
		}

		return $this->render('photos', compact('model'));
	}

	/**
	 * Removes photo from gallery
	 * @param $gid
	 * @param $pid
	 * @return \yii\web\Response
	 * @throws \Exception
	 */
	public function actionRemovePhoto( $gid, $pid ) {
		/** @var ImageGallery $imageGallery */
		$imageGallery = ImageGallery::findOne([
			'gallery_id' => $gid,
			'image_id' => $pid
		]);
		$imageGallery->delete();
		return $this->redirect(['photos', 'id' => $gid]);
	}

	/**
	 * Adds photos to gallery
	 * @param $id
	 * @return string
	 */
	public function actionAddPhotos( $id ) {
		$model = new GalleryAddPhotosForm($id);
		if ($model->load(Yii::$app->request->post()) && $model->validate()) {
			$model->savePhotosToGallery();
			return $this->redirect(['photos', 'id' => $id]);
		}

		$dataProvider = $model->search();
		return $this->render('selectPhotos', compact('model', 'dataProvider'));
	}

	/**
	 * Returns gallery items for CKEditor gallery plugin
	 * @return array
	 */
	public function actionGalleryItems() {
		$session = Yii::$app->session;
		if (!$session['language_id'])
			$session['language_id'] = LanguageRecord::getMainLanguageId();

		$items = ArrayHelper::map(Gallery::find()->andWhere(['language_id' => $session['language_id']])->orderBy(['updated_at' => SORT_DESC])->all(), 'title', 'id');

		$response = Yii::$app->response;
		$response->format = Response::FORMAT_JSON;
		$response->data = $items;

		return $response;
	}

	/**
	 * Returns gallery display types for CKEditor gallery plugin
	 * @return array
	 */
	public function actionDisplayItems() {
		$items = Gallery::getDisplayTypes();

		$response = Yii::$app->response;
		$response->format = Response::FORMAT_JSON;
		$response->data = $items;

		return $response;
	}
}
