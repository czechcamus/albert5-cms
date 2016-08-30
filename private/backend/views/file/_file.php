<?php
/* @var $this yii\web\View */
/* @var $model common\models\FileRecord */
/* @var $lid integer Language ID */

use common\models\FileRecord;
use yii\helpers\Html;
use yii\helpers\StringHelper;
use yii\helpers\Url;
?>

<div class="col-xs-6 col-sm-4 col-md-3 col-lg-2 file-view" title="<?= $model->getFileTitle($lid); ?>">
	<div class="file-controls">
		<?= Html::button('<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>', [
			'value' => Url::to(['update-texts', 'id' => $model->id]),
			'title' => Yii::t('back', 'Update texts of file'),
			'class' => 'showModalButton btn btn-link'
		]); ?>
	</div>
	<div class="file-title"><?= StringHelper::truncate($model->title, 12); ?></div>
	<?php
		switch ($model->type) {
			case (FileRecord::TYPE_FILE):
				echo Html::a(Html::img('@web/' . Yii::$app->params['fileDefaultDir'] . 'pdffile.png', [
					'width' => 100,
					'height' => 100
				]), '@web/' . Yii::$app->params['fileUploadDir'] . $model->filename);
				break;
			case (FileRecord::TYPE_SOUND):
				$fileExt = array_pop(explode(".", strtolower($model->filename)));
				echo backend\utilities\CircleAudioWidget::widget([
					'mediaOptions' => [
						$fileExt => Yii::getAlias('@web/') . Yii::$app->params['fileUploadDir'] . $model->filename,
					],
					'jsOptions' => [
						'supplied' => "mp3, m4a, ogg, oga",
						'wmode' => "window",
					],
				]);
				break;
		}
	?>
</div>