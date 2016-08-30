<?php

use backend\assets\FormAsset;
use common\models\LanguageRecord;
use yii\bootstrap\ActiveField;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\LanguageRecord */
/* @var $form yii\bootstrap\ActiveForm */

FormAsset::register($this);
?>

<div class="language-form">

    <?php $form = ActiveForm::begin([
        'layout' => 'horizontal',
        'fieldClass' => ActiveField::className(),
        'fieldConfig' => Yii::$app->params['fieldConfig'],
        'options' => [
	        'id' => 'language-form'
        ]
    ]); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => 20]) ?>
    <?= $form->field($model, 'acronym')->textInput(['maxlength' => 3, 'style' => 'width: 5em']) ?>

	<?= /** @noinspection PhpUndefinedMethodInspection */
	$form->field($model, 'boxes')->inline()->checkboxList([
		LanguageRecord::PROPERTY_MAIN => Yii::t('back', 'Main'),
		LanguageRecord::PROPERTY_ACTIVE => Yii::t('back', 'Active')
	], [
		'id' => 'modal-boxes',
		'data-main' => ($model->existsMoreLanguageRecords($model->isNewRecord ? true : false) && !$model->main) == true ? 0 : 1
	]) ?>

    <div class="form-group modal-footer">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('back', 'Create') : Yii::t('back', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	    <button type="button" class="btn btn-default" data-dismiss="modal"><?= Yii::t('back', 'Close'); ?></button>
    </div>

    <?php ActiveForm::end(); ?>

</div>
