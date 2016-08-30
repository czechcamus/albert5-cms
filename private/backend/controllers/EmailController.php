<?php
/**
 * Created by PhpStorm.
 * User: Pavel
 * Date: 9.7.2015
 * Time: 7:25
 */

namespace backend\controllers;


use backend\models\EmailForm;
use backend\models\EmailSearch;
use backend\utilities\BackendController;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;

class EmailController extends BackendController {

	/**
	 * @inheritdoc$
	 */
	public function behaviors() {
		return [
			'verbs'  => [
				'class'   => VerbFilter::className(),
				'actions' => [
					'delete' => [ 'post' ],
				],
			],
			'access' => [
				'class' => AccessControl::className(),
				'rules' => [
					[
						'roles' => [ 'user' ],
						'allow' => true
					]
				]
			]
		];
	}

	/**
	 * Lists all Email models.
	 * @return mixed
	 */
	public function actionIndex() {
		$searchModel  = new EmailSearch();
		$dataProvider = $searchModel->search( Yii::$app->request->queryParams );

		return $this->render( 'index', compact( 'searchModel', 'dataProvider' ) );
	}

	/**
	 * Creates a new Email model.
	 * If creation is successful, the browser will be redirected to the 'index' page.
	 * @return mixed
	 */
	public function actionCreate() {
		$model           = new EmailForm();
		$model->scenario = 'create';

		if ( $model->load( Yii::$app->request->post() ) && $model->validate() ) {
			$model->saveEmail();

			$session = Yii::$app->session;
			$session->setFlash( 'info', Yii::t( 'back', 'New poll successfully added!' ) );

			return $this->redirect( [ 'index' ] );
		} elseif ( Yii::$app->request->isAjax ) {
			return $this->renderAjax( '_form', compact( 'model' ) );
		}

		return $this->render( '_form', compact( 'model' ) );
	}

	/**
	 * Updates an existing Email model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 *
	 * @param integer $id
	 *
	 * @return mixed
	 */
	public function actionUpdate( $id ) {
		$model           = new EmailForm( $id );
		$model->scenario = 'update';

		if ( $model->load( Yii::$app->request->post() ) && $model->validate() ) {
			$model->saveEmail( false );

			$session = Yii::$app->session;
			$session->setFlash( 'info', Yii::t( 'back', 'Email successfully updated!' ) );

			return $this->redirect( [ 'index' ] );
		} elseif ( Yii::$app->request->isAjax ) {
			return $this->renderAjax( '_form', compact( 'model' ) );
		}

		return $this->render( '_form', compact( 'model' ) );
	}

	/**
	 * Deletes an existing Email model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 *
	 * @param integer $id
	 *
	 * @return mixed
	 */
	public function actionDelete( $id ) {
		$model = new EmailForm( $id );
		$model->deleteEmail();

		$session = Yii::$app->session;
		$session->setFlash( 'info', Yii::t( 'back', 'Email successfully deleted!' ) );

		return $this->redirect( [ 'index' ] );
	}
}