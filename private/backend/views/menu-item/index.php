<?php

use backend\components\LanguageButtonDropdown;
use backend\components\ParentItemsTree;
use common\models\LanguageRecord;
use common\models\MenuRecord;
use common\models\WebRecord;
use kartik\sortinput\SortableInput;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $model \backend\models\MenuItemForm */
/* @var $menu \common\models\MenuRecord */
/* @var $web_id integer */
/* @var $menu_id integer */

$session = Yii::$app->session;

$menu = $model->getMenu();
$this->title = Yii::t('back', 'Menu Items');
$this->params['breadcrumbs'][] = $menu->web->title . ' - ' . $menu->title;
$modelClass = Yii::t('back', 'Menu item');
?>

<div>

    <?php
    if($model->language_id || LanguageRecord::existsMoreLanguageRecords(false, true)) {

        echo '<div class="pull-right">';
        echo LanguageButtonDropdown::widget([
            'routeBase' => ['menu-item/index']
        ]);
        echo '</div>';
    }
    ?>

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?php
        $createUrl = ['menu-item/create', 'mid' => $model->menu_id];
        echo Html::button(
            Yii::t('back', 'Create {modelClass}', compact('modelClass')),
            [
                'value' => Url::to($createUrl),
                'title' => Yii::t('back', 'Create {modelClass}', compact('modelClass')),
                'class' => 'showModalButton btn btn-success'
            ]
        ) ?>
    </p>

    <?php Pjax::begin(); ?>

		<div class="row">
		    <?= Html::beginForm(['menu-item/index']); ?>
		    <div class="form-group col-md-6">
			    <?= Html::label(Yii::t('back', 'Web'), 'web_id'); ?>
			    <?= Html::dropDownList('web_id', $web_id, WebRecord::getWebOptions(), [
				    'onchange' => 'this.form.submit()',
				    'class' => 'form-control'
			    ]); ?>
		    </div>
		    <?= Html::endForm(); ?>

		    <?= Html::beginForm(['menu-item/index']); ?>
		    <div class="form-group col-md-6">
			    <?= Html::label(Yii::t('back', 'Menu'), 'menu_id'); ?>
			    <?= Html::dropDownList('menu_id', $menu_id, MenuRecord::getMenuOptions(), [
				    'onchange' => 'this.form.submit()',
				    'class' => 'form-control'
			    ]); ?>
		    </div>
		    <?= Html::endForm(); ?>
	    </div>

		<?php if ($model->parent_id): ?>
			<div class="panel panel-default parent-menu-tree">
				<div class="panel-heading"><?= Yii::t('back', 'Parent levels') ?></div>
				<div class="panel-body">
					<?= ParentItemsTree::widget([
						'parent_id' => $model->parent_id,
						'menu_id' => $model->menu_id
					]); ?>
				</div>
			</div>
			<h4><?= Yii::t('back', 'Parent item') . ': <strong>' . $model->getParentItem()->title; ?></strong></h4>
		<?php endif; ?>

		<?php if ($session->hasFlash('info')): ?>
			<div class="alert alert-success">
				<?= $session->getFlash('info'); ?>
			</div>
		<?php endif; ?>

		<?php if ($items = $model->getMenuItems()): ?>

		    <?php if (!$session->hasFlash('info')): ?>
		        <p><span class="label label-info"><?= Yii::t('back', 'Info'); ?></span> <?= Yii::t('back', 'Drag and drop menu items for change their order.'); ?></p>
		    <?php endif; ?>

		    <?php $form = ActiveForm::begin([
		        'id' => 'menu-item-order-form',
		    ]); ?>

			<div class="sortable-field">
		    <?= $form->field($model, 'item_order', ['enableLabel' => false])->widget(SortableInput::className(), [
		        'name'=> 'sort_list',
		        'items' => $items,
		        'hideInput' => true
		    ]); ?>
			</div>

		    <p><span class="label label-warning"><?= Yii::t('back', 'Important'); ?></span> <?= Yii::t('back', 'Don\'t forget click "Save items order" button after reordering menu items.'); ?></p>

		    <?= Html::submitButton(Yii::t('back', 'Save items order'), [
		        'id' => 'save-order-btn',
		        'class' => 'btn btn-primary'
		    ]); ?>

		    <?php ActiveForm::end(); ?>

		<?php endif; ?>

	<?php Pjax::end(); ?>

</div>
