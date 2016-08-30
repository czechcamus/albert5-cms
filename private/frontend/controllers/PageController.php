<?php
/**
 * Created by PhpStorm.
 * User: Pavel
 * Date: 1.10.2015
 * Time: 10:25
 */

namespace frontend\controllers;


use common\models\MenuItemRecord;
use frontend\models\ArticleContent;
use frontend\models\MenuContent;
use frontend\models\SearchContent;
use frontend\utilities\FrontEndController;
use frontend\utilities\MenuFilter;
use Yii;
use yii\helpers\Url;

class PageController extends FrontEndController
{
	/** @var $_menuContent MenuItemRecord */
	private $_menuContent;

	/**
	 * @inheritdoc
	 */
	public function behaviors() {
		return [
			'menu'   => [
				'class' => MenuFilter::className(),
				'only'  => [ 'home', 'menu', 'article' ]
			]
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

	/**
	 * Action for generating homepage
	 * @return string
	 */
	public function actionHome() {
		Url::remember('', 'page');
		if ( $this->_menuContent->layout ) {
			$viewName = $this->_menuContent->layout->filename;
		} else {
			$viewName = 'homepage';
		}
		return $this->render( $viewName, [ 'menuContent' => $this->_menuContent ] );
	}

	/**
	 * Action for generating page
	 * @param null $type
	 * @return string
	 */
	public function actionMenu( $type = null ) {
		Url::remember('', 'page');
		if ( $this->_menuContent->layout ) {
			$viewName = $this->_menuContent->layout->filename;
		} else {
			if ( $this->_menuContent->content_type == MenuItemRecord::CONTENT_PAGE ) {
				$viewName = 'page';
			} else {
				$viewName = 'category';
			}
		}

		if ($type == 'pdf') {
			/** @noinspection PhpUndefinedFieldInspection */
			$pdf                       = Yii::$app->pdf;
			$pdf->content              = $this->renderPartial('pdf/' . $viewName, [
				'content' => $this->_menuContent
			]);
			$pdf->options['title']     = $this->_menuContent->title;
			$pdf->methods['SetFooter'] = ['{PAGENO}'];
			return $pdf->render();
		} else {
			return $this->render( $viewName, [ 'menuContent' => $this->_menuContent ] );
		}
	}

	/**
	 * Action for generating article
	 * @param $ida
	 * @param null $type
	 * @return string
	 */
	public function actionArticle( $ida, $type = null ) {
		Url::remember('', 'page');
		/** @var ArticleContent $articleContent */
		$articleContent = ArticleContent::findOne( $ida );
		if ( $articleContent->layout ) {
			$viewName = $articleContent->layout->filename;
		} else {
			$viewName = 'article';
		}

		if ($type == 'pdf') {
			/** @noinspection PhpUndefinedFieldInspection */
			$pdf                       = Yii::$app->pdf;
			$pdf->content              = $this->renderPartial('pdf/' . $viewName, [
				'content' => $articleContent
			]);
			$pdf->options['title']     = $articleContent->title;
			$pdf->methods['SetFooter'] = ['{PAGENO}'];
			return $pdf->render();

		} else {
			return $this->render( $viewName, [
				'menuContent'    => $this->_menuContent,
				'articleContent' => $articleContent
			] );
		}
	}

	/**
	 * Search action
	 * @return string
	 */
	public function actionSearch() {
		Url::remember('', 'search');

		$model = new SearchContent();
		if ($model->load(Yii::$app->request->get()) && $model->validate()) {
			if ($model->target == SearchContent::TARGET_WEB) {
				$dataProvider = $model->getItems();
				$this->layout = 'search-content';
				return $this->render('search', compact('model', 'dataProvider'));
			} else {
				return $this->redirect(Yii::$app->params['catalogSearchUrl'] . $model->q);
			}
		} else {
			return $this->redirect(Url::previous());
		}
	}

	/**
	 * Tag action
	 * @param $tag
	 * @return string
	 */
	public function actionTag($tag) {
		Url::remember('', 'tag');

		$model = new SearchContent();
		$dataProvider = $model->getTagItems($tag);
		$this->layout = 'search-content';
		return $this->render('tag', compact('model', 'dataProvider', 'tag'));
	}

	/**
	 * Sets $_menuContent property
	 * @param integer $menuId
	 */
	public function setMenuContent( $menuId ) {
		if ($menuId && ($menuContent = MenuContent::findOne($menuId)))
			$this->_menuContent = $menuContent;
	}
}