<?php

use backend\components\LanguageButtonDropdown;
use common\models\LanguageRecord;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\GallerySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$session = Yii::$app->session;
if (!$session['language_id'])
	$session['language_id'] = LanguageRecord::getMainLanguageId();

$this->title = Yii::t('back', 'Galleries');
$this->params['breadcrumbs'][] = $this->title;
$modelClass = Yii::t('back', 'Gallery');
?>
<div>

	<?php
	if($session['language_id'] || LanguageRecord::existsMoreLanguageRecords(false, true)) {

		echo '<div class="pull-right">';
		echo LanguageButtonDropdown::widget([
			'routeBase' => ['gallery/index']
		]);
		echo '</div>';
	}
	?>

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::button(
	        Yii::t('back', 'Create {modelClass}', compact('modelClass')),
	        [
		        'value' => Url::to(['gallery/create']),
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
	    'id' => 'gallery-list',
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
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
	            'template' => '{update} {delete} {photos}',
	            'buttons' => [
		            'update' => function ($url) {
			            return Html::button('<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>', [
				            'value' => $url,
				            'title' => Yii::t('back', 'Update category'),
				            'class' => 'showModalButton btn btn-link',
				            'style' => 'padding: 0'
			            ]);
		            },
		            'delete' => function ($url, $model) {
			            return $model->main ? '' : Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, [
				            'title' => Yii::t('back', 'Delete category'),
				            'data-confirm' => Yii::t('back', 'Are you sure you want to delete this category?'),
				            'data-method' => 'post',
				            'data-pjax' => '0',
			            ]);
		            },
		            'photos' => function ($url) {
			            return Html::a('<span class="glyphicon glyphicon-camera"></span>', $url, [
				            'title' => Yii::t('back', 'Manage photos')
			            ]);
		            }
	            ]
            ],
        ],
    ]); ?>

</div>