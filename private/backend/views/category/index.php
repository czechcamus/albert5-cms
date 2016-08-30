<?php

use backend\components\LanguageButtonDropdown;
use common\models\LanguageRecord;
use common\models\MenuItemRecord;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\CategorySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$session = Yii::$app->session;
if (!$session['language_id'])
	$session['language_id'] = LanguageRecord::getMainLanguageId();

$this->title = Yii::t('back', 'Categories');
$this->params['breadcrumbs'][] = $this->title;
$modelClass = Yii::t('back', 'Category');
?>
<div>

	<?php
	if($session['language_id'] || LanguageRecord::existsMoreLanguageRecords(false, true)) {

		echo '<div class="pull-right">';
		echo LanguageButtonDropdown::widget([
			'routeBase' => ['category/index']
		]);
		echo '</div>';
	}
	?>

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::button(
	        Yii::t('back', 'Create {modelClass}', compact('modelClass')),
	        [
		        'value' => Url::to(['category/create']),
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

    <?= /** @noinspection PhpUnusedParameterInspection */
    GridView::widget([
	    'id' => 'category-list',
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'title',
	        [
		        'attribute' => 'main',
		        'filter' => [
			        Yii::t('back', 'No'),
			        Yii::t('back', 'Yes')
		        ],
		        'value' => function ($model) {
			        return $model->main == 1 ? Yii::t('back', 'yes') : Yii::t('back', 'no');
		        }
	        ],
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
	            'template' => '{update} {delete} {createFromContent} {articles}',
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
		            'createFromContent' => function ($url, $model, $key) {
			            return Yii::$app->user->can('manager') ? Html::button('<span class="glyphicon glyphicon-plus" aria-hidden="true"></span>', [
				            'value' => Url::toRoute([
					            'menu-item/create-from-content',
					            'content_type' => MenuItemRecord::CONTENT_CATEGORY,
					            'content_id' => $key
				            ]),
				            'title' => Yii::t('back', 'Create menu item from category'),
				            'class' => 'showModalButton btn btn-link',
				            'style' => 'padding: 0'
			            ]) : '';
		            },
		            'articles' => function ($url, $model, $key) {
			            return Html::a('<span class="glyphicon glyphicon-list" aria-hidden="true"></span>', [
				            'articles',
				            'id' => $key
			            ],
			            [
				            'title' => Yii::t('back', 'List of Articles'),
				            'class' => 'btn btn-link',
				            'style' => 'padding: 0'
			            ]);
		            }
	            ]
            ],
        ],
    ]); ?>

</div>