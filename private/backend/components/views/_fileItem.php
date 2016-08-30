<?php
/* @var $this yii\web\View */
/* @var $model common\models\FileRecord */

use common\models\FileRecord;
use pavlinter\display\DisplayImage;
use yii\helpers\Html;
use yii\helpers\Url;

?>

<div class="col-xs-12 col-sm-6 file-view">
	<div class="file-controls">
		<?= Html::button('<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>', [
			'value' => Url::to(['file/update', 'id' => $model->id]),
			'title' => Yii::t('back', 'Update file'),
			'class' => 'showModalButton btn btn-link'
		]); ?>
		<?= Html::a('<span class="glyphicon glyphicon-trash" aria-hidden="true"></span>', ['file/delete', 'id' => $model->id], [
			'title' => Yii::t('back', 'Delete file'),
			'data-confirm' => Yii::t('back', 'Are you sure, you want to delete this file?'),
			'data-pjax' => '0',
		]); ?>
	</div>
	<?php if ($model->title) {
		echo '<div class="file-title">';
		echo $model->type == FileRecord::TYPE_FILE ? Html::a($model->title, '@web/' . Yii::$app->params['fileUploadDir'] . $model->filename) : $model->title;
		echo '</div>';
	} ?>
	<?php if ($model->type == FileRecord::TYPE_IMAGE) {
		echo DisplayImage::widget([
			'width' => 150,
			'height' => 150,
			'image' => $model->filename,
			'category' => 'all'
		]);
	} else {
		echo Html::a(Html::img('@web/' . Yii::$app->params['fileDefaultDir'] . 'pdffile.png', [
			'width' => 150,
			'height' => 150
		]), '@web/' . Yii::$app->params['fileUploadDir'] . $model->filename);
	} ?>
</div>