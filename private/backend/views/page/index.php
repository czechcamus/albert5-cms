<?php

use backend\components\LanguageButtonDropdown;
use common\models\LanguageRecord;
use common\models\MenuItemRecord;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\PageSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$session = Yii::$app->session;
if (!$session['language_id'])
	$session['language_id'] = LanguageRecord::getMainLanguageId();

$this->title = Yii::t('back', 'Pages');
$this->params['breadcrumbs'][] = $this->title;
$modelClass = Yii::t('back', 'Page');
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

    <p class="show-loading">
        <?= Html::a(Yii::t('back', 'Create {modelClass}', compact('modelClass')), ['create'],
	        [
		        'class' => 'btn btn-success'
	        ]
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
	            'template' => '<span class="show-loading">{update}</span> {delete} <span class="show-loading">{copy}</span> {createFromContent}',
	            'buttons' => [
		            'copy' => function ($url, $model, $key) {
			            return Html::a('<span class="glyphicon glyphicon-duplicate" aria-hidden="true"></span>', [
				            'page/copy',
				            'id' => $key
			            ],
				            [
					            'title' => Yii::t('back', 'Copy'),
					            'class' => 'btn btn-link',
					            'style' => 'padding: 0 0 3px'
				            ]);
		            },
		            'createFromContent' => function ($url, $model, $key) {
			            return Yii::$app->user->can('manager') ? Html::button('<span class="glyphicon glyphicon-plus" aria-hidden="true"></span>', [
				            'value' => Url::toRoute([
					            'menu-item/create-from-content',
					            'content_type' => MenuItemRecord::CONTENT_PAGE,
					            'content_id' => $key
				            ]),
				            'title' => Yii::t('back', 'Create menu item from page'),
				            'class' => 'showModalButton btn btn-link',
				            'style' => 'padding: 0'
			            ]) : '';
		            }
	            ]
            ]
        ],
    ]); ?>

</div>
