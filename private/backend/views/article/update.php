<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Article */

/** @var \backend\controllers\ArticleController $controller */
$controller = $this->context;
$modelClass = Yii::t('back', 'Article');
$this->title = Yii::t('back', 'Update {modelClass}: ', compact('modelClass')) . ' ' . $model->title;
if ($categoryModel = $controller->getCategory()) {
	$this->params['breadcrumbs'][] = ['label' => Yii::t('back', 'Categories'), 'url' => ['category/index']];
	$this->params['breadcrumbs'][] = ['label' => Yii::t('back', 'Articles') . ' ' . Yii::t('back', 'of category') . ': ' . $categoryModel->title, 'url' => ['category/articles', 'id' => $categoryModel->id]];
} else {
	$this->params['breadcrumbs'][] = ['label' => Yii::t('back', 'Articles'), 'url' => ['index']];
}
$this->params['breadcrumbs'][] = $this->title;
?>
<div>

	<h1><?= Html::encode($this->title) ?></h1>

	<?= $this->render('_form', compact('model')) ?>

</div>