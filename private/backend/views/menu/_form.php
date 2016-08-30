<?php

use backend\assets\FormAsset;
use common\models\MenuRecord;
use yii\bootstrap\ActiveField;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\MenuRecord */
/* @var $form yii\bootstrap\ActiveForm */

FormAsset::register($this);
?>

<div>

    <?php $form = ActiveForm::begin([
        'layout' => 'horizontal',
        'fieldClass' => ActiveField::className(),
        'fieldConfig' => Yii::$app->params['fieldConfig'],
        'options' => [
	        'id' => 'menu-form'
        ]
    ]); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => 255]) ?>

    <?= $form->field($model, 'text_id')->textInput(['maxlength' => 255]) ?>

    <?= $form->field($model, 'web_id')->dropDownList($model->getWebOptions()) ?>

	<?= /** @noinspection PhpUndefinedMethodInspection */
	$form->field($model, 'boxes')->inline()->checkboxList([
		MenuRecord::PROPERTY_MAIN => Yii::t('back', 'Main'),
		MenuRecord::PROPERTY_ACTIVE => Yii::t('back', 'Active'),
		MenuRecord::PROPERTY_PUBLIC => Yii::t('back', 'Public')
	], [
		'id' => 'modal-boxes',
		'data-main' => (MenuRecord::existsMoreMenuRecords($model->isNewRecord ? true : false) && !$model->main) == true ? 0 : 1
	]) ?>


	<div class="form-group modal-footer">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('back', 'Create') : Yii::t('back', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	    <button type="button" class="btn btn-default" data-dismiss="modal"><?= Yii::t('back', 'Close'); ?></button>
    </div>

    <?php ActiveForm::end(); ?>

</div>
