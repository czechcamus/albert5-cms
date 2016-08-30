<?php
/* @var $this yii\web\View */
/* @var $model common\models\FileRecord */
/* @var $lid integer Language ID */

use pavlinter\display\DisplayImage;
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
		echo DisplayImage::widget([
			'width' => 100,
			'height' => 100,
			'image' => $model->filename,
			'category' => 'all'
		]);
	?>
</div>