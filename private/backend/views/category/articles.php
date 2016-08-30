<?php
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ArrayDataProvider */
/* @var $categoryModel \common\models\Category */

use yii\grid\GridView;
use yii\helpers\Html;

$session = Yii::$app->session;

$this->title = Yii::t('back', 'Articles') . ' ' . Yii::t('back', 'of category') . ': ' . $categoryModel->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('back', 'Categories'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$modelClass = Yii::t('back', 'Article');
?>

<div>

	<h1><?= Html::encode($this->title) ?></h1>

	<p>
		<?= Html::a(Yii::t('back', 'Create {modelClass}', compact('modelClass')), ['article/create', 'category_id' => $categoryModel->id],
			['class' => 'btn btn-success']
		) ?>
	</p>

	<?php if ($session->hasFlash('info')): ?>
		<div class="alert alert-success">
			<?= $session->getFlash('info'); ?>
		</div>
	<?php endif; ?>

	<?= /** @noinspection PhpUnusedParameterInspection */
	GridView::widget([
		'dataProvider' => $dataProvider,
		'columns' => [
			['class' => 'yii\grid\SerialColumn'],

			'title',
			[
				'attribute' => 'public',
				'filter' => [
					Yii::t('back', 'No'),
					Yii::t('back', 'Yes')
				],
				'value' => function ($model) {
					return $model->public == 1 ? Yii::t('back', 'yes') : Yii::t('back', 'no');
				}
			],
			[
				'attribute' => 'active',
				'filter' => [
					Yii::t('back', 'No'),
					Yii::t('back', 'Yes')
				],
				'value' => function ($model) {
					return $model->active == 1 ? Yii::t('back', 'yes') : Yii::t('back', 'no');
				}
			],

			[
				'class' => 'yii\grid\ActionColumn',
				'controller' => 'article',
				'template' => '{update} {delete} {copy}',
				'buttons' => [
					'update' => function ($url, $model, $key) use ($categoryModel) {
						return Html::a( '<span class="glyphicon glyphicon-pencil"></span>', [
							'article/update',
							'id' => $model->id,
							'category_id' => $categoryModel->id
						],
						[
							'title'      => Yii::t('back', 'Update'),
							'aria-label' => Yii::t('back', 'Update'),
							'data-pjax'  => '0',
						]);
					},
					'delete' => function ($url, $model, $key) use ($categoryModel) {
						return Html::a('<span class="glyphicon glyphicon-trash"></span>', [
							'article/delete',
							'id' => $model->id,
							'category_id' => $categoryModel->id
						],
						[
							'title' => Yii::t('back', 'Delete'),
							'aria-label' => Yii::t('back', 'Delete'),
							'data-confirm' => Yii::t('back', 'Are you sure you want to delete this item?'),
							'data-method' => 'post',
							'data-pjax' => '0'
						]);
					},
					'copy' => function ($url, $model, $key) use ($categoryModel) {
						return Html::a('<span class="glyphicon glyphicon-duplicate" aria-hidden="true"></span>', [
							'article/copy',
							'id' => $model->id,
							'category_id' => $categoryModel->id
						],
						[
							'title' => Yii::t('back', 'Copy'),
							'class' => 'btn btn-link',
							'style' => 'padding: 0 0 3px'
						]);
					}
				]
			]
		],
	]); ?>

</div>
