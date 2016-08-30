<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$session = Yii::$app->session;

$this->title = Yii::t('back', 'Layouts');
$this->params['breadcrumbs'][] = $this->title;
$modelClass = Yii::t('back', 'Layout');
?>
<div class="layout-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php if (Yii::$app->user->can('admin')): ?>
        <p>
            <?= Html::button(
	            Yii::t('back', 'Create {modelClass}', compact('modelClass')),
	            [
		            'value' => Url::to(['layout/create']),
		            'title' => Yii::t('back', 'Create {modelClass}', compact('modelClass')),
		            'class' => 'showModalButton btn btn-success'
	            ]
            ) ?>
        </p>
    <?php endif; ?>

	<?php if ($session->hasFlash('info')): ?>
		<div class="alert alert-success">
			<?= $session->getFlash('info'); ?>
		</div>
	<?php endif; ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'summary' => '',
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'title',
            'filename',
            [
                'attribute' => 'main',
                'value' => function ($model) {
                    return $model->main == 1 ? Yii::t('back', 'yes') : Yii::t('back', 'no');
                }
            ],
            [
                'attribute' => 'active',
                'value' => function ($model) {
                    return $model->active == 1 ? Yii::t('back', 'yes') : Yii::t('back', 'no');
                }
            ],
            [
                'attribute' => 'content',
                'value' => function ($model) {
                    return $model->getContentOptionText();
                }
            ],

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update} {delete}',
	            'buttons' => [
		            'update' => function ($url) {
			            return Html::button('<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>', [
				            'value' => $url,
				            'title' => Yii::t('back', 'Update layout'),
				            'class' => 'showModalButton btn btn-link',
				            'style' => 'padding: 0'
			            ]);
		            },
	                'delete' => function ($url, $model) {
		                return $model->main ? '' : Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, [
			                'title' => Yii::t('back', 'Delete layout'),
			                'data-confirm' => Yii::t('back', 'Are you sure you want to delete this layout?'),
			                'data-method' => 'post',
			                'data-pjax' => '0'
		                ]);
	                }
	            ]
            ]
        ]
    ]); ?>

</div>
