<?php

use backend\components\LanguageButtonDropdown;
use common\models\LanguageRecord;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\NewsletterSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$session = Yii::$app->session;
if (!$session['language_id'])
	$session['language_id'] = LanguageRecord::getMainLanguageId();

$this->title = Yii::t('back', 'Newsletters');
$this->params['breadcrumbs'][] = $this->title;
$modelClass = Yii::t('back', 'Newsletter');
?>
<div>

	<?php
	if($session['language_id'] || LanguageRecord::existsMoreLanguageRecords(false, true)) {

		echo '<div class="pull-right">';
		echo LanguageButtonDropdown::widget([
			'routeBase' => ['page/index']
		]);
		echo '</div>';
	}
	?>

    <h1><?= Html::encode($this->title) ?></h1>

	<?php if (Yii::$app->user->can('manager')): ?>
	    <p class="show-loading">
	        <?= Html::a(Yii::t('back', 'Create {modelClass}', compact('modelClass')), ['create'],
		        [
			        'class' => 'btn btn-success'
		        ]
	        ) ?>
	    </p>
	<?php endif; ?>

	<?php if ($session->hasFlash('info')): ?>
		<div class="alert alert-success">
			<?= $session->getFlash('info'); ?>
		</div>
	<?php endif; ?>

    <?= /** @noinspection PhpUnusedParameterInspection */
    GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'title',
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
		        'attribute' => 'sendDateTime',
		        'label' => Yii::t('back', 'Time of send'),
		        'format' => ['datetime', 'dd.MM.y HH:mm'],
		        'value' => function ($model) {
			        return $model->content_date && $model->content_time ? $model->content_date . ' ' . $model->content_time : null;
		        }
	        ],

            [
	            'class' => 'yii\grid\ActionColumn',
	            'template' => '<span class="show-loading">{update}</span> {delete} {preview} <span class="show-loading">{send}</span>',
	            'buttons' => [
		            'preview' => function ($url, $model, $key) {
			            return Html::a('<span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span>', [
				                'newsletter/view',
				                'id' => $key
				            ],
				            [
					            'title' => Yii::t('back', 'Preview newsletter'),
					            'class' => 'btn btn-link',
					            'style' => 'padding: 0 0 3px',
					            'target' => '_blank'
				            ]);
		            },
		            'send' => function ($url, $model, $key) {
			            return Html::a('<span class="glyphicon glyphicon-send" aria-hidden="true"></span>', [
				            'newsletter/send',
				            'id' => $key
			            ],
			            [
				            'title' => Yii::t('back', 'Send newsletter now'),
				            'class' => 'btn btn-link',
				            'style' => 'padding: 0 0 3px'
			            ]);
		            }
	            ]
            ]
        ],
    ]); ?>

</div>
