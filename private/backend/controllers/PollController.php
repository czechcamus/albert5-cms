<?php
/**
 * Created by PhpStorm.
 * User: Pavel
 * Date: 9.7.2015
 * Time: 7:25
 */

namespace backend\controllers;


use backend\models\PollForm;
use backend\models\PollSearch;
use backend\utilities\BackendController;
use common\models\LanguageRecord;
use common\models\PollRecord;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class PollController extends BackendController
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
			]
		];
	}

	/**
	 * Lists all Poll models.
	 * @return mixed
	 */
	public function actionIndex()
	{
		$searchModel = new PollSearch();
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams);

		return $this->render('index', compact('searchModel', 'dataProvider'));
	}

	/**
	 * Creates a new Poll model.
	 * If creation is successful, the browser will be redirected to the 'index' page.
	 * @return mixed
	 */
	public function actionCreate()
	{
		$model = new PollForm();
		$model->scenario = 'create';

		if ($model->load(Yii::$app->request->post()) && $model->validate()) {
			$model->savePoll();

			$session = Yii::$app->session;
			$session->setFlash('info', Yii::t('back', 'New poll successfully added!'));

			return $this->redirect(['index']);
		} elseif (Yii::$app->request->isAjax) {
			return $this->renderAjax('_form', compact('model'));
		}

		return $this->render('_form', compact('model'));
	}

	/**
	 * Updates an existing Poll model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionUpdate($id)
	{
		$model = new PollForm($id);
		$model->scenario = 'update';

		if ($model->load(Yii::$app->request->post()) && $model->validate()) {
			$model->savePoll(false);

			$session = Yii::$app->session;
			$session->setFlash('info', Yii::t('back', 'Poll successfully updated!'));

			return $this->redirect(['index']);
		} elseif (Yii::$app->request->isAjax) {
			return $this->renderAjax('_form', compact('model'));
		}

		return $this->render('_form', compact('model'));
	}

	/**
	 * Deletes an existing Poll model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionDelete($id)
	{
		$model = new PollForm($id);
		$model->deletePoll();

		$session = Yii::$app->session;
		$session->setFlash('info', Yii::t('back', 'Poll successfully deleted!'));

		return $this->redirect( [ 'index' ] );
	}

	/**
	 * Results of given poll.
	 * @param integer $id
	 * @return mixed
	 * @throws NotFoundHttpException
	 */
	public function actionResults($id)
	{
		if (($model = PollRecord::findOne($id)) !== null) {
			return $this->render('results', compact('model'));
		}

		throw new NotFoundHttpException(Yii::t('back', 'The requested page does not exist.'));
	}

	/**
	 * Returns poll items for CKEditor poll plugin
	 * @return \yii\console\Response|Response
	 */
	public function actionPollItems() {
		$session = Yii::$app->session;
		if (!$session['language_id'])
			$session['language_id'] = LanguageRecord::getMainLanguageId();

		$items = ArrayHelper::map(PollRecord::find()->andWhere(['language_id' => $session['language_id']])->orderBy(['updated_at' => SORT_DESC])->all(), 'question', 'id');

		$response = Yii::$app->response;
		$response->format = Response::FORMAT_JSON;
		$response->data = $items;

		return $response;
	}

	/**
	 * Returns poll display types for CKEditor poll plugin
	 * @return array
	 */
	public function actionDisplayItems() {
		$items = ['Koláč', 'Sloupce'];

		$response = Yii::$app->response;
		$response->format = Response::FORMAT_JSON;
		$response->data = $items;

		return $response;
	}
}