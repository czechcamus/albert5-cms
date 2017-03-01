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
use backend\utilities\BackendController;
use backend\utilities\CategoryFilter;
use backend\utilities\SynchronizeFiles;
use common\models\Article;
use common\models\Category;
use common\models\LanguageRecord;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\Response;

class ArticleController extends BackendController
{
	private $_category;

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
			'category' => CategoryFilter::className(),
			'synchronize' => [
				'class' => SynchronizeFiles::className(),
				'only'  => [ 'create', 'copy', 'update' ]
			]
		];
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