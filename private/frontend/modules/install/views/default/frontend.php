<?php
/* @var $this yii\web\View */
/* @var $model \frontend\modules\install\models\WebForm */

use frontend\modules\install\Module;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\jui\Spinner;

$this->title                   = Module::t('inst', 'Albert 5 CMS') . ' - ' . Module::t('inst', 'frontend settings');
$this->params['breadcrumbs'][] = $this->title;
$template = '{label} <div>{input}{error}{hint}</div>'
?>
<div class="frontend-view">
	<h1><?= Html::encode($this->title) ?></h1>

	<p><?= Module::t('inst', 'Please fill out the following fields to setup frontend application') ;?>:</p>

	<div class="row">
		<div class="col-lg-5">
			<?php $form = ActiveForm::begin(['id' => 'form-frontend']); ?>

			<?= $form->field($model, 'maxDisplayImageWidth', compact('template'))->widget( Spinner::className()) ?>

			<?= $form->field($model, 'galleryLinkImageSize', compact('template'))->widget( Spinner::className()) ?>

			<?= $form->field($model, 'galleryThumbnailSize', compact('template'))->widget( Spinner::className()) ?>

			<?= $form->field($model, 'defaultTagsCount', compact('template'))->widget( Spinner::className()) ?>

			<?= $form->field($model, 'defaultTagItemSizeStep', compact('template'))->widget( Spinner::className()) ?>

			<?= $form->field($model, 'googleMapsApiKey') ?>

			<div class="form-group">
				<?= Html::a(Module::t('inst', 'back'), ['web'], ['class' => 'btn btn-default']) ?>
				<?= Html::submitButton(Module::t('inst', 'ok'), ['class' => 'btn btn-primary', 'name' => 'web-button']) ?>
			</div>

			<?php ActiveForm::end(); ?>
		</div>
	</div>
</div>
