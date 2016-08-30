<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$session = Yii::$app->session;

$this->title = Yii::t('back', 'Webs');
$this->params['breadcrumbs'][] = $this->title;
$modelClass = Yii::t('back', 'web');
?>
<div>

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
	    <?= Html::button(
		    Yii::t('back', 'Create {modelClass}', compact('modelClass')),
		    [
			    'value' => Url::to(['web/create']),
			    'title' => Yii::t('back', 'Create {modelClass}', compact('modelClass')),
			    'class' => 'showModalButton btn btn-success'
		    ]
	    ) ?>
    </p>

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
            'weburl',
            'theme',
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
                'attribute' => 'public',
                'value' => function ($model) {
                    return $model->public == 1 ? Yii::t('back', 'yes') : Yii::t('back', 'no');
                }
            ],

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update} {delete}',
                'buttons' => [
	                'update' => function ($url) {
		                return Html::button('<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>', [
			                'value' => $url,
			                'title' => Yii::t('back', 'Update web'),
			                'class' => 'showModalButton btn btn-link',
			                'style' => 'padding: 0'
		                ]);
	                },
                    'delete' => function ($url, $model) {
                        return $model->main ? '' : Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, [
                            'title' => Yii::t('back', 'Delete web'),
                            'data-confirm' => Yii::t('back', 'Are you really sure you want to delete this web and his whole content?'),
                            'data-method' => 'post',
                            'data-pjax' => '0'
                        ]);
                    }
                ]
            ]
        ],
    ]); ?>

</div>
