<?php
/**
 * Created by PhpStorm.
 * User: Pavel
 * Date: 17.9.2015
 * Time: 19:38
 */

namespace backend\utilities;


use backend\controllers\ArticleController;
use common\models\Category;
use yii\base\ActionFilter;

/**
 * Class CategoryFilter ensures Category relation for connected Article
 * @package backend\utilities
 */
class CategoryFilter extends ActionFilter
{
	/**
	 * @param \yii\base\Action $action
	 *
	 * @return bool
	 */
	public function beforeAction($action) {
		$request = \Yii::$app->request;

		if ($request->get('category_id')) {
			$id = $request->get('category_id');
			/** @var Category $category */
			$category = Category::findOne($id);
			if ($category) {
				/** @var  $controller ArticleController */
				$controller = $this->owner;
				$controller->setCategory($category);
			}
		}

		return parent::beforeAction($action);
	}
}