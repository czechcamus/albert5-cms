<?php
/**
 * Created by PhpStorm.
 * User: Pavel
 * Date: 4.7.2015
 * Time: 13:12
 */

namespace backend\controllers;


use backend\models\FileSearch;
use backend\models\ImageSearch;
use backend\utilities\BackendController;
use backend\utilities\SynchronizeFiles;
use common\models\FileRecord;
use common\models\FileTextRecord;
use common\models\LanguageRecord;
use common\models\Sound;
use Yii;
use yii\base\InvalidValueException;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\web\Cookie;
use yii\web\Response;

class FileController extends BackendController
{
	/**
	 * @inheritdoc$
	 */
	public function behaviors()
	{
		return [
			'access' => [
				'class' => AccessControl::className(),
				'rules' => [
					[
						'roles' => ['user'],
						'allow' => true
					]
				]
			],
			'synchronizeImages'   => [
				'class' => SynchronizeFiles::className(),
				'syncTypes' => ['images'],
				'only'  => [ 'images' ]
			],
			'synchronizeFiles'   => [
				'class' => SynchronizeFiles::className(),
				'syncTypes' => ['files'],
				'only'  => [ 'files' ]
			]
		];
	}

	/**
	 * Images managing
	 * @return string|\yii\web\Response
	 */
	public function actionImages()
	{
		$searchModel = new ImageSearch();
		$dataProvider = $searchModel->search(Yii::$app->request->post());

		return $this->render('imageIndex', compact('searchModel', 'dataProvider'));
	}

	/**
	 * Images server files managing
	 * @return string|\yii\web\Response
	 */
	public function actionImagesManage()
	{
		$cookies = Yii::$app->response->cookies;
		$cookies->add(new Cookie([
			'name' => 'manageImagesTime',
			'value' => time()
		]));

		return $this->render('files', [
			'type' => 'images'
		]);
	}

	/**
	 * Files managing
	 * @return string|\yii\web\Response
	 */
	public function actionFiles()
	{
		$searchModel = new FileSearch();
		$dataProvider = $searchModel->search(Yii::$app->request->post());

		return $this->render('fileIndex', compact('searchModel', 'dataProvider'));
	}

	/**
	 * Server files managing
	 * @return string|\yii\web\Response
	 */
	public function actionFilesManage()
	{
		$cookies = Yii::$app->response->cookies;
		$cookies->add(new Cookie([
			'name' => 'manageFilesTime',
			'value' => time()
		]));

		return $this->render('files', [
			'type' => 'files'
		]);
	}

	/**
	 * Updates texts for file in given language
	 * @param $id integer id of file
	 * @return string|Response
	 */
	public function actionUpdateTexts( $id )
	{
		/** @var FileRecord $file */
		$file = FileRecord::findOne($id);
		if ($file) {

			$session = Yii::$app->session;
			if ( ! $session['language_id'] ) {
				$session['language_id'] = LanguageRecord::getMainLanguageId();
			}

			$model = FileTextRecord::find()->where( [
				'file_id'     => $id,
				'language_id' => $session['language_id']
			] )->one();

			if ( ! $model ) {
				$model              = new FileTextRecord();
				$model->title       = $file->title;
				$model->file_id     = $id;
				$model->language_id = $session['language_id'];
			}

			if ( $model->load( Yii::$app->request->post() ) && $model->save() ) {
				if ( $model->file->type == FileRecord::TYPE_IMAGE ) {
					return $this->redirect( [ 'images' ] );
				} else {
					return $this->redirect( [ 'files' ] );
				}
			} elseif ( Yii::$app->request->isAjax ) {
				return $this->renderAjax( '_textsForm', compact( 'model' ) );
			}

			return $this->render( '_textsForm', compact( 'model' ) );

		} else {
			throw new InvalidValueException(Yii::t('back', 'No file record found of this ID') . ': ' . $id);
		}
	}

	/**
	 * Returns sound items for CKEditor sound plugin
	 * @return array
	 */
	public function actionSoundItems() {
		$items = ArrayHelper::map(Sound::find()->all(), 'title', 'id');

		$response = Yii::$app->response;
		$response->format = Response::FORMAT_JSON;
		$response->data = $items;

		return $response;
	}
}