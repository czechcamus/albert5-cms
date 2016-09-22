<?php
/* @var $this yii\web\View */
/* @var $model common\models\Image */
/* @var $form yii\bootstrap\ActiveForm */

use pavlinter\display\DisplayImage;
use yii\helpers\Html;

$time = Yii::$app->formatter->asDatetime($model->file_time);
?>

<div class="col-xs-6 col-md-3 col-lg-2 photo-view">
	<div class="photo-controls">
		<?= Html::checkbox('GalleryAddPhotosForm[addedImagesIds][' . $model->id .']'); ?>
	</div>
	<div class="photo-info" title="<?= Yii::t('back', 'last update time') . ': ' . $time; ?>">
		<div class="filename-info" title="<?= $model->filename; ?>">
			<strong><?= $model->filename; ?></strong>
		</div>
		<div class="time-info" title="<?= Yii::t('back', 'last update time') . ': ' . $time; ?>">
			<span class="glyphicon glyphicon-time" aria-hidden="true"></span> <?= $time; ?>
		</div>
	</div>
	<?= DisplayImage::widget([
		'width' => 100,
		'height' => 100,
		'image' => $model->filename,
		'category' => 'all'
	]); ?>
</div>