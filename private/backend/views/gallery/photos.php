<?php

use common\models\LanguageRecord;
use kartik\sortable\Sortable;
use kartik\sortinput\SortableInput;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model \backend\models\GalleryPhotosForm */

$session = Yii::$app->session;
if (!$session['language_id'])
	$session['language_id'] = LanguageRecord::getMainLanguageId();

$this->title = $model->title . ' - ' . Yii::t('back', 'photos in gallery');
$this->params['breadcrumbs'][] = ['label' => Yii::t('back', 'Galleries'), 'url' => ['gallery/index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div>

	<h1><?= Html::encode($this->title) ?></h1>

	<p>
		<?= Html::a(
			Yii::t('back', 'Add photos'), ['gallery/add-photos', 'id' => $model->item_id],
			[
				'class' => 'btn btn-success'
			]
		) ?>
	</p>

	<?php if ($model->photos): ?>

		<?php if ($session->hasFlash('info')): ?>
			<div class="alert alert-success">
				<?= $session->getFlash('info'); ?>
			</div>
		<?php else: ?>
			<p><span class="label label-info"><?= Yii::t('back', 'Info'); ?></span> <?= Yii::t('back', 'Drag and drop photos for change their order.'); ?></p>
		<?php endif; ?>


		<?php $form = ActiveForm::begin([
			'id' => 'photos-order-form'
		]); ?>

		<div class="sortable-photos">
			<?= $form->field($model, 'item_order', ['enableLabel' => false])->widget(SortableInput::className(), [
				'sortableOptions' => [
					'type' => Sortable::TYPE_GRID
				],
				'name'=> 'sort_list',
				'items' => $model->getPhotosWithControl(),
				'hideInput' => true
			]); ?>
		</div>

		<p><span class="label label-warning"><?= Yii::t('back', 'Important'); ?></span> <?= Yii::t('back', 'Don\'t forget click "Save photos order" button after reordering photos.'); ?></p>

		<?= Html::submitButton(Yii::t('back', 'Save photos order'), [
			'id' => 'save-order-btn',
			'class' => 'btn btn-primary'
		]); ?>

		<?php ActiveForm::end(); ?>

	<?php endif; ?>

</div>