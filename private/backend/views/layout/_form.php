<?php
/* @var $this yii\web\View */
/* @var $model common\models\LayoutRecord */
/* @var $form yii\bootstrap\ActiveForm */

use backend\assets\FormAsset;
use common\models\LayoutRecord;
use yii\bootstrap\ActiveField;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

FormAsset::register($this);
?>

<div class="layout-form">

    <?php $form = ActiveForm::begin([
        'layout' => 'horizontal',
        'fieldClass' => ActiveField::className(),
        'fieldConfig' => Yii::$app->params['fieldConfig'],
        'options' => [
	        'id' => 'layout-form'
        ]
    ]); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => 255]) ?>

    <?= $form->field($model, 'filename')->textInput(['maxlength' => 45]) ?>

	<?= $form->field($model, 'content')->dropDownList($model->getContentOptions()) ?>

	<?= /** @noinspection PhpUndefinedMethodInspection */
	$form->field($model, 'boxes')->inline()->checkboxList([
		LayoutRecord::PROPERTY_MAIN => Yii::t('back', 'Main'),
		LayoutRecord::PROPERTY_ACTIVE => Yii::t('back', 'Active')
	], [
		'id' => 'modal-boxes',
		'data-main' => ($model->existsMoreLayoutRecords($model->isNewRecord ? true : false) && !$model->main) == true ? 0 : 1
	]) ?>

	<div class="form-group modal-footer">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('back', 'Create') : Yii::t('back', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
		<button type="button" class="btn btn-default" data-dismiss="modal"><?= Yii::t('back', 'Close'); ?></button>
    </div>

    <?php ActiveForm::end(); ?>

</div>
