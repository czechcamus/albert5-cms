<?php

use backend\assets\MenuItemFormAsset;
use backend\models\MenuItemForm;
use common\models\MenuItemRecord;
use yii\bootstrap\ActiveField;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\MenuItemForm */
/* @var $form yii\bootstrap\ActiveForm */

MenuItemFormAsset::register($this);
?>

<div>

    <?php $form = ActiveForm::begin([
        'layout' => 'horizontal',
        'fieldClass' => ActiveField::className(),
        'fieldConfig' => Yii::$app->params['fieldConfig'],
	    'options' => [
		    'id' => 'menu-item-form'
	    ]
    ]); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => 255]) ?>

    <?= $form->field($model, 'parent_id')->dropDownList($model->getParentItems(), ['prompt' => '-- ' . Yii::t('back', 'No parent') . ' --']) ?>

    <?= $form->field($model, 'content_type')->dropDownList($model->getContentTypes(),
		[
			'prompt' => '-- ' . Yii::t('back', 'Not selected') . ' --',
			'onchange' => '
				$.fn.changeForm($(this).val());
		        $.get("' . Url::to(['menu-item/content-list-options']) . '",
		        {
		            tid: $(this).val(),
		            mid: ' . $model->menu_id . '
		        })
		        .done(function(data) {
		            $("#' . Html::getInputId($model, 'content_id') . '").html(data);
		        });
		        $.get("' . Url::to(['menu-item/layout-list-options']) . '",
		        {
		            tid: $(this).val(),
		            mid: ' . $model->menu_id . '
		        })
		        .done(function(data) {
		            $("#' . Html::getInputId($model, 'layout_id') . '").html(data);
	        });'
		])
    ?>

    <div id="link_url"<?php if ($model->content_type != MenuItemRecord::CONTENT_LINK) echo 'style="display: none;"'; ?>>
        <?= $form->field($model, 'link_url')->textInput(['maxlength' => 255]) ?>
    </div>

    <div id="link_target"<?php if ($model->content_type != MenuItemRecord::CONTENT_LINK) echo 'style="display: none;"'; ?>>
        <?= $form->field($model, 'link_target')->dropDownList($model->getLinkTargets()) ?>
    </div>

    <div id="content_id"<?php if ($model->content_type == MenuItemRecord::CONTENT_LINK) echo 'style="display: none;"'; ?>>
        <?= $form->field($model, 'content_id')->dropDownList($model->getContentListOptions()) ?>
        <div id="layout_id">
            <?= $form->field($model, 'layout_id')->dropDownList($model->getLayoutListOptions()) ?>
        </div>
    </div>

    <?= /** @noinspection PhpUndefinedMethodInspection */
    $form->field($model, 'boxes')->inline()->checkboxList([
        MenuItemForm::PROPERTY_MAIN => Yii::t('back', 'Main'),
        MenuItemForm::PROPERTY_ACTIVE => Yii::t('back', 'Active'),
        MenuItemForm::PROPERTY_PUBLIC => Yii::t('back', 'Public')
    ]) ?>

    <div class="form-group modal-footer">
        <?= Html::submitButton($model->scenario == 'create' ? Yii::t('back', 'Create') : Yii::t('back', 'Update'), [
            'class' => $model->scenario == 'create' ? 'btn btn-success' : 'btn btn-primary'
        ]) ?>
	    <button type="button" class="btn btn-default" data-dismiss="modal"><?= Yii::t('back', 'Close'); ?></button>
    </div>

    <?php ActiveForm::end(); ?>

</div>
