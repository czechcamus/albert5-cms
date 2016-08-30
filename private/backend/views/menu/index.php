<?php

use common\models\WebRecord;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $web_id integer */

$session = Yii::$app->session;

$this->title = Yii::t('back', 'Menus');
$this->params['breadcrumbs'][] = $this->title;
$modelClass = Yii::t('back', 'Menu');
?>
<div>

    <h1><?= Html::encode($this->title) ?></h1>

    <?php if (Yii::$app->user->can('admin')): ?>
        <p>
            <?= Html::button(
	            Yii::t('back', 'Create {modelClass}', compact('modelClass')),
	            [
		            'value' => Url::to(['menu/create']),
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

    <?php Pjax::begin(); ?>

		<div class="row">
		<?= Html::beginForm(); ?>
			<div class="form-group col-md-6">
				<?= Html::label(Yii::t('back', 'Web'), 'web_id'); ?>
				<?= Html::dropDownList('web_id', $web_id, WebRecord::getWebOptions(), [
					'onchange' => 'this.form.submit()',
					'class' => 'form-control'
				]); ?>
			</div>
		<?= Html::endForm(); ?>
		</div>

		<p><strong><?= Yii::t('back', 'Menus of web'); ?></strong></p>

	    <?= GridView::widget([
	        'dataProvider' => $dataProvider,
	        'summary' => '',
	        'columns' => [
	            ['class' => 'yii\grid\SerialColumn'],

		        'title',
	            'text_id',
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
							    'title' => Yii::t('back', 'Update menu'),
							    'class' => 'showModalButton btn btn-link',
							    'style' => 'padding: 0'
						    ]);
					    },
					    'delete' => function ($url, $model) {
						    return $model->main ? '' : Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, [
							    'title' => Yii::t('back', 'Delete menu'),
							    'data-confirm' => Yii::t('back', 'Are you really sure you want to delete this menu and his whole content?'),
							    'data-method' => 'post',
							    'data-pjax' => '0'
						    ]);
					    }
				    ]
	            ]
	        ]
	    ]); ?>

    <?php Pjax::end(); ?>

</div>
