<?php

use backend\assets\FormAsset;
use backend\models\PollForm;
use kartik\date\DatePicker;
use yii\bootstrap\ActiveField;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $model \backend\models\PollForm */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $answerFieldsCount integer */

$answerFieldsCount = $model->scenario == 'create' ? 2 : count($model->answers);

FormAsset::register($this);
?>

<div>

	<?php $form = ActiveForm::begin([
		'layout' => 'horizontal',
		'fieldClass' => ActiveField::className(),
		'fieldConfig' => Yii::$app->params['fieldConfig'],
		'options' => [
			'id' => 'web-form'
		]
	]); ?>

	<?= $form->field($model, 'question')->textInput(['maxlength' => 255]) ?>

	<?= $form->field($model, 'end_date')->widget(DatePicker::className(), [
		'pluginOptions' => [
			'autoclose' => true
		],
		'options' => [
			'class' => 'form-control',
			'placeholder' => Yii::t('back', 'Date')
		]
	]) ?>

	<?= /** @noinspection PhpUndefinedMethodInspection */
	$form->field($model, 'boxes')->inline()->checkboxList([
		PollForm::PROPERTY_MAIN => Yii::t('back', 'Main'),
		PollForm::PROPERTY_ACTIVE => Yii::t('back', 'Active')
	], [
		'id' => 'modal-boxes',
		'data-public' => 0
	]) ?>

	<?php if ($model->isAnswersEditable): ?>
	<div class="form-group">
		<div class="col-sm-9 col-sm-offset-2">
		<?= Html::a(Yii::t('back', 'Add answer field'), '#',
			[
				'title' => Yii::t('back', 'Add answer field'),
				'class' => 'add_field_button btn btn-success'
			]
		) ?>
		</div>
	</div>
	<?php endif; ?>

	<div class="input_fields_wrap form-group" data-fields="<?= $answerFieldsCount; ?>">
		<div>
			<label class="control-label col-sm-2">1.</label>
			<div class="col-sm-9"><?= Html::textInput('PollForm[answers][]', $model->scenario == 'create' ? null : $model->answers[0], [
					'class' => 'form-control',
					'disabled' => $model->isAnswersEditable ? false : "disabled"
				]); ?></div>
		</div>
		<div>
			<label class="control-label col-sm-2">2.</label>
			<div class="col-sm-9"><?= Html::textInput('PollForm[answers][]', $model->scenario == 'create' ? null : $model->answers[1], [
					'class' =>'form-control',
					'disabled' => $model->isAnswersEditable ? false : "disabled"
				]); ?></div>
		</div>
		<?php if ($answerFieldsCount > 2) {
			echo $model->renderAnswerFields();
		} ?>
	</div>

	<div class="form-group modal-footer">
		<?= Html::submitButton($model->scenario == 'create' ? Yii::t('back', 'Create') : Yii::t('back', 'Update'), ['class' => $model->scenario == 'create' ? 'btn btn-success' : 'btn btn-primary']) ?>
		<?= Html::a(Yii::t('back', 'Close'), ['index'], [
			'class' => 'btn btn-default'
		]); ?>
	</div>

	<?php ActiveForm::end(); ?>

</div>
