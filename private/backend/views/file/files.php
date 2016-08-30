<?php
/* @var $this yii\web\View */
/* @var $type string */

use iutbay\yii2kcfinder\KCFinderAsset;
use yii\helpers\Html;

$register = KCFinderAsset::register($this);
$kcfinderUrl = $register->baseUrl;

$this->title = $type == 'images' ? Yii::t('back', 'Manage image files') : Yii::t('back', 'Manage files');
$this->params['breadcrumbs'][] = ['label' => $type == 'images' ? Yii::t('back', 'Images') : Yii::t('back', 'Files'), 'url' => $type == 'images' ? ['file/images'] : ['file/files']];
$this->params['breadcrumbs'][] = $this->title;
$modelClass = Yii::t('back', 'Image');
?>

<!--suppress HtmlUnknownTarget -->
<div class="file-index">

	<h1><?= Html::encode($this->title) ?></h1>

	<iframe name="files" src="<?= $kcfinderUrl; ?>/browse.php?type=<?= $type; ?>&lang=cs" style="border: none; width: 100%; height: 600px;"></iframe>

</div>
