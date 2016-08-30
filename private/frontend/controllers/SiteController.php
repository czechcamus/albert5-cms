<?php
namespace frontend\controllers;

use common\models\Category;
use common\models\ContentRecord;
use common\models\Gallery;
use common\models\PollAnswerRecord;
use frontend\models\ContactForm;
use frontend\models\SearchContent;
use frontend\utilities\FrontEndController;
use Yii;
use yii\helpers\StringHelper;
use yii\helpers\Url;
use yii\filters\VerbFilter;
use yii\web\Cookie;
use Zelenin\yii\extensions\Rss\RssView;

/**
 * Site controller
 */
class SiteController extends FrontEndController {

	/**
	 * @inheritdoc
	 */
	public function behaviors() {
		return [
			'verbs'  => [
				'class'   => VerbFilter::className(),
				'actions' => [
					'vote'   => [ 'post' ]
				],
			],
		];
	}

	/**
	 * @inheritdoc
	 */
	public function actions() {
		return [
			'error'   => [
				'class' => 'yii\web\ErrorAction',
			],
			'captcha' => [
				'class'           => 'yii\captcha\CaptchaAction',
				'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
			],
		];
	}

	public function actionGallery( $id ) {
		$gallery      = Gallery::findOne( $id );
		$this->layout = 'main-gallery';

		return $this->render( 'gallery', compact( 'gallery' ) );
	}

	public function actionContent( $id ) {
		$backUrl = \Yii::$app->request->referrer;
		$content = ContentRecord::findOne( $id );
		$this->layout = 'basic-content';

		return $this->render( 'content', compact( 'content', 'backUrl' ) );
	}

	public function actionCategory( $id ) {
		$backUrl = \Yii::$app->request->referrer;
		$category = Category::findOne( $id );
		$this->layout = 'basic-category';

		return $this->render( 'category', compact( 'category', 'backUrl' ) );
	}

	public function actionContact( $type = null ) {
		$model = new ContactForm();
		$viewName = 'contact';
		if ($model->load(Yii::$app->request->post()) && $model->send(Yii::$app->params['adminEmail'])) {
			Yii::$app->session->setFlash( 'info',
				Yii::t( 'front', 'Your message was successfuly submitted. Thank you!' ) );

			return $this->refresh();
		} else {
			if ($type == 'pdf') {
				/** @noinspection PhpUndefinedFieldInspection */
				$pdf                       = Yii::$app->pdf;
				$pdf->content              = $this->renderPartial('pdf/' . $viewName);
				$pdf->options['title']     = Yii::t('front', 'Contacts');
				$pdf->methods['SetFooter'] = ['{PAGENO}'];
				return $pdf->render();

			} else {
				return $this->render( $viewName, compact( 'model' ) );
			}
		}
	}


	public function actionVote() {
		$answerId = Yii::$app->request->post( 'answer' );
		if ( $answerId ) {
			/** @var PollAnswerRecord $answer */
			$answer = PollAnswerRecord::findOne( $answerId );
			if ( $answer ) {
				if ( $answer->voices ) {
					PollAnswerRecord::updateAllCounters( [ 'voices' => 1 ], [ 'id' => $answerId ] );
				} else {
					PollAnswerRecord::updateAll( [ 'voices' => 1 ], [ 'id' => $answerId ] );
				}
				$cookies = Yii::$app->response->cookies;

				$cookies->add( new Cookie( [
					'name'  => 'poll_' . $answer->poll_id,
					'value' => '1'
				] ) );
			}
		}
		$this->goBack( Url::previous( 'page' ) );
	}

	public function actionRss() {

		$model        = new SearchContent();
		$dataProvider = $model->getItems( true );

		$response = Yii::$app->getResponse();
		$headers  = $response->getHeaders();

		$headers->set( 'Content-Type', 'application/rss+xml; charset=utf-8' );

		/** @noinspection PhpUnusedParameterInspection */
		$response->content = RssView::widget( [
			'dataProvider' => $dataProvider,
			'channel'      => [
				'title'       => Yii::$app->name,
				'link'        => Url::toRoute( '/', true ),
				'description' => Yii::t( 'front', 'Articles' ),
				'language'    => Yii::$app->language
			],
			'items'        => [
				'title'       => function ( $model, $widget ) {
					return $model['title'];
				},
				'description' => function ( $model, $widget ) {
					return StringHelper::truncateWords( $model['perex'], 50 );
				},
				'link'        => function ( $model, $widget ) {
					return Url::toRoute( [ 'site/content', 'id' => $model['id'] ], true );
				},
				'guid'        => function ( $model, $widget ) {
					$date = \DateTime::createFromFormat( 'Y-m-d H:i:s', $model['updated_at'] );

					return Url::toRoute( [ 'site/content', 'id' => $model['id'] ],
						true ) . ' ' . $date->format( DATE_RSS );
				},
				'pubDate'     => function ( $model, $widget ) {
					$date = \DateTime::createFromFormat( 'Y-m-d H:i:s', $model['updated_at'] );

					return $date->format( DATE_RSS );
				}
			]
		] );
	}
}
