<?php
/**
 * Created by PhpStorm.
 * User: Pavel
 * Date: 9.7.2015
 * Time: 7:25
 */

namespace backend\controllers;


use backend\models\ArticleForm;
use backend\models\ArticleSearch;
use backend\utilities\CategoryFilter;
use backend\utilities\ContentController;
use common\models\Article;
use common\models\Category;
use common\models\ContentFieldRecord;
use common\models\LanguageRecord;
use Yii;
use yii\helpers\ArrayHelper;
use yii\web\Response;

class ArticleController extends ContentController
{
	private $_category;

	/**
	 * @inheritdoc$
	 */
	public function behaviors()
	{
		return ArrayHelper::merge(parent::behaviors(), ['category' => CategoryFilter::className()]);
	}

	/**
	 * Lists all Article models.
	 * @return mixed
	 */
	public function actionIndex()
	{
		$searchModel = new ArticleSearch();
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams);

		return $this->render('index', compact('searchModel', 'dataProvider'));
	}

	/**
	 * Creates a new Article model.
	 * If creation is successful, the browser will be redirected to the 'index' page.
	 * @return mixed
	 */
	public function actionCreate()
	{
		$model = new ArticleForm();
		$model->scenario = 'create';

		if ($model->load(Yii::$app->request->post()) && $model->validate()) {
			$model->saveArticle();

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
			$session->setFlash('info', Yii::t('back', 'New article successfully added!'));

			if ($this->_category) {
				return$this->redirect(['category/articles', 'id' => $this->_category->id]);
			} else {
				return $this->redirect(['index']);
			}
		} else {
			if ($this->_category) {
				$model->categoryBoxes = [];
				$model->categoryBoxes[] = $this->_category->id;
			}
			return $this->render('create', compact('model'));
		}
	}

	/**
	 * Creates a new Article model from an existing model.
	 * If creation is successful, the browser will be redirected to the 'index' page.
	 * @param $id
	 * @return mixed
	 */
	public function actionCopy($id)
	{
		$model = new ArticleForm($id, true);
		$model->scenario = 'create';

		if ($model->load(Yii::$app->request->post()) && $model->validate()) {
			$model->saveArticle();

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
			$session->setFlash('info', Yii::t('back', 'New article successfully added!'));

			if ($this->_category) {
				return$this->redirect(['category/articles', 'id' => $this->_category->id]);
			} else {
				return $this->redirect(['index']);
			}
		} else {
			if ($this->_category) {
				$model->categoryBoxes = [];
				$model->categoryBoxes[] = $this->_category->id;
			}
			return $this->render('create', compact('model'));
		}
	}

	/**
	 * Updates an existing Article model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionUpdate($id)
	{
		$model = new ArticleForm($id);

		if ($model->load(Yii::$app->request->post()) && $model->validate()) {
			$model->saveArticle(false);

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
			$session->setFlash('info', Yii::t('back', 'Article successfully updated!'));

			if ($this->_category) {
				return$this->redirect(['category/articles', 'id' => $this->_category->id]);
			} else {
				return $this->redirect( [ 'index' ] );
			}
		} else {
			return $this->render('update', compact('model'));
		}
	}

	/**
	 * Deletes an existing Article model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionDelete($id)
	{
		$model = new ArticleForm($id);
		$model->deleteArticle();

		$session = Yii::$app->session;
		$session->setFlash('info', Yii::t('back', 'Article successfully deleted!'));

		if ($this->_category) {
			return$this->redirect(['category/articles', 'id' => $this->_category->id]);
		} else {
			return $this->redirect( [ 'index' ] );
		}
	}

	/**
	 * Returns poll items for CKEditor article plugin
	 * @return \yii\console\Response|Response
	 */
	public function actionArticleItems() {
		$session = Yii::$app->session;
		if (!$session['language_id'])
			$session['language_id'] = LanguageRecord::getMainLanguageId();

		$items = ArrayHelper::map(Article::find()->activeStatus()->andWhere(['language_id' => $session['language_id']])->orderBy(['updated_at' => SORT_DESC])->all(), 'title', 'id');

		$response = Yii::$app->response;
		$response->format = Response::FORMAT_JSON;
		$response->data = $items;

		return $response;

	}

	/**
	 * @param Category $category
	 */
	public function setCategory( $category ) {
		$this->_category = $category;
	}

	/**
	 * Gets $_category property
	 * @return Category mixed
	 */
	public function getCategory() {
		return $this->_category;
	}
}