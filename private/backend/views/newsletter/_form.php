<?php
/* @var $this yii\web\View */
/* @var $model backend\models\NewsletterForm */
/* @var $form yii\bootstrap\ActiveForm */

use backend\models\NewsletterForm;
use backend\utilities\CKEditor;
use yii\bootstrap\ActiveField;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

?>

<div class="row">

	<div class="col-sm-12 col-md-9">

		<?php $form = ActiveForm::begin([
			'fieldClass' => ActiveField::className()
		]); ?>

		<?= $form->field($model, 'title')->textInput(['maxlength' => 255]) ?>

		<?= $form->field($model, 'description')->widget(CKEditor::className(), [
			'options' => ['rows' => 6],
			'preset' => 'custom',
			'clientOptions' => [
				'height' => 400,
				'toolbarGroups' => [
					['name' => 'clipboard', 'groups' => ['mode', 'undo', 'selection', 'clipboard', 'doctools']],
					['name' => 'styles'],
					['name' => 'editing', 'groups' => ['tools']],
					['name' => 'basicstyles', 'groups' => ['basicstyles', 'cleanup']],
					'/',
					['name' => 'paragraph', 'groups' => ['list', 'indent', 'align', 'blocks']],
					['name' => 'links'],
					['name' => 'insert'],
					['name' => 'others'],
				],
				'removeButtons' => 'Templates,CreateDiv,Styles,Font,FontSize,Smiley,Iframe,Flash,PageBreak,Anchor,Gallery,Sound,Youtube,Poll'
			]
		]) ?>

	</div>

	<div class="col-sm-12 col-md-3">

		<?= /** @noinspection PhpUndefinedMethodInspection */
		$form->field($model, 'boxes')->inline()->checkboxList([
			NewsletterForm::PROPERTY_ACTIVE => Yii::t('back', 'Active')
		], [
			'id' => 'modal-boxes',
			'data-public' => 0,
			'data-main' => 0
		]) ?>

		<?= $form->field($model, 'layout_id')->dropDownList($model->getLayoutListOptions()) ?>

		<div class="form-group">
			<?= Html::submitButton($model->scenario == 'create' ? Yii::t('back', 'Create') : Yii::t('back', 'Update'), [
				'class' => $model->scenario == 'create' ? 'btn btn-success' : 'btn btn-primary']) ?>
		</div>

		<?php ActiveForm::end(); ?>

	</div>

</div>
