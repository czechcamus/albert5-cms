<?php
/* @var $this yii\web\View */
/* @var $model \frontend\modules\install\models\SignupForm */

use frontend\modules\install\Module;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

$this->title                   = Module::t('inst', 'Albert 5 CMS') . ' - ' . Module::t('inst', 'admin user creation');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-signup">
	<h1><?= Html::encode($this->title) ?></h1>

	<p><?= Module::t('inst', 'Please fill out the following fields to create admin user') ;?>:</p>

	<div class="row">
		<div class="col-lg-5">
			<?php $form = ActiveForm::begin(['id' => 'form-signup']); ?>

			<?= $form->field($model, 'username')->textInput(['autofocus' => true]) ?>

			<?= $form->field($model, 'email') ?>

			<?= $form->field($model, 'password')->passwordInput() ?>

			<div class="form-group">
				<?= Html::submitButton(Module::t('inst', 'next'), ['class' => 'btn btn-primary', 'name' => 'signup-button']) ?>
			</div>

			<?php ActiveForm::end(); ?>
		</div>
	</div>
</div>
