<?php

namespace backend\controllers;

use backend\models\CategoryForm;
use backend\utilities\BackendController;
use common\models\Category;
use Yii;
use yii\data\ArrayDataProvider;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;

/**
 * CategoryController implements the CRUD actions for CategoryRecord model.
 */
class CategoryController extends BackendController
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
			            'roles' => ['manager'],
			            'allow' => true
		            ]
	            ]
            ]
        ];
    }

    /**
     * Lists all Category models.
     * @return mixed
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * Creates a new CategoryForm model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new CategoryForm;
	    $model->scenario = 'create';

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
	        $model->saveCategory();

	        $session = Yii::$app->session;
	        $session->setFlash('info', Yii::t('back', 'New category successfully added!'));

            return $this->redirect(['index']);
        } elseif (Yii::$app->request->isAjax) {
	        return $this->renderAjax('_form', compact('model'));
        }

	    return $this->render('_form', compact('model'));
    }

    /**
     * Updates an existing CategoryForm model.
     * If update is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = new CategoryForm($id);
	    $model->scenario = 'update';

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
	        $model->saveCategory(false);

	        $session = Yii::$app->session;
	        $session->setFlash('info', Yii::t('back', 'Category successfully updated!'));

            return $this->redirect(['index']);
        } elseif (Yii::$app->request->isAjax) {
	        return $this->renderAjax('_form', compact('model'));
        }

	    return $this->render('_form', compact('model'));
    }

    /**
     * Deletes an existing Category model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
	    $model = new CategoryForm($id);
        $model->deleteCategory();

	    $session = Yii::$app->session;
	    $session->setFlash('info', Yii::t('back', 'Category successfully deleted!'));

        return $this->redirect(['index']);
    }

	/**
	 * Lists Articles of this Category.
	 * @param $id
	 * @return mixed
	 * @throws NotFoundHttpException
	 */
    public function actionArticles($id)
    {
	    /** @var Category $categoryModel */
        $categoryModel = $this->findModel($id);
        $dataProvider = new ArrayDataProvider([
	        'allModels' => $categoryModel->articles,
	        'sort' => [
		        'attributes' => ['title', 'public', 'active']
	        ]
        ]);

        return $this->render('articles', compact('categoryModel', 'dataProvider'));
    }

	/**
	 * Finds the Category model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 * @param $id
	 * @return null|Category
	 * @throws NotFoundHttpException
	 */
	protected function findModel($id)
	{
		if (($model = Category::findOne($id)) !== null)
			return $model;

		throw new NotFoundHttpException(Yii::t('back', 'The requested page does not exist.'));
	}
}
