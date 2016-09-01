<?php
/* @var $this yii\web\View */
/* @var $model \frontend\modules\install\models\WebForm */

use frontend\modules\install\Module;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

$this->title                   = Module::t('inst', 'Albert 5 CMS') . ' - ' . Module::t('inst', 'web creation');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="web-view">
	<h1><?= Html::encode($this->title) ?></h1>

	<p><?= Module::t('inst', 'Please fill out the following fields to create web application') ;?>:</p>

	<div class="row">
		<div class="col-lg-5">
			<?php $form = ActiveForm::begin(['id' => 'form-web']); ?>

			<?= $form->field($model, 'languageAcronym')->dropDownList($model->getLanguageOptions(), ['autofocus' => true]) ?>

			<?= $form->field($model, 'webTitle') ?>

			<?= $form->field($model, 'webOwner') ?>

			<?= $form->field($model, 'adminEmail') ?>

			<?= $form->field($model, 'supportEmail') ?>

			<?= $form->field($model, 'sendingEmail') ?>

			<?= $form->field($model, 'sendingEmailTitle') ?>

			<div class="form-group">
				<?= Html::submitButton(Module::t('inst', 'next'), ['class' => 'btn btn-primary', 'name' => 'web-button']) ?>
			</div>

			<?php ActiveForm::end(); ?>
		</div>
	</div>
</div>
