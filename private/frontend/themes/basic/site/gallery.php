<?php
/* @var $this yii\web\View */
/* @var $gallery Gallery */

use common\models\Gallery;
use frontend\utilities\FrontEndHelper;
use pavlinter\display\DisplayImage;
use yii\helpers\Html;

$this->title = Yii::$app->name . ': ' . $gallery->title;
$this->params['title'] = $gallery->title;
$this->params['description'] = $gallery->description;

echo '<div class="row">';
echo '<div id="links" class="col s12">';
foreach ( $gallery->imageGalleries as $imageGallery ) {
	/** @var \common\models\Image $image */
	$image = $imageGallery->image;
	$title = $image->getFileTitle( FrontEndHelper::getLanguageIdFromAcronym() );
	$thumbnail = DisplayImage::widget( [
		'width'    => \Yii::$app->params['galleryThumbnailSize'],
		'height'   => \Yii::$app->params['galleryThumbnailSize'],
		'options'  => [
			'class' => 'img-responsive hoverable',
			'title' => $title
		],
		'category' => 'all',
		'image'    => $image->filename
	] );
	echo Html::a($thumbnail, '@web/admin/' . Yii::$app->params['imageUploadDir'] . $image->filename, [
		'title' => $title,
		'data-description' => $image->getFileDescription( FrontEndHelper::getLanguageIdFromAcronym() ),
		'data-gallery' => true
	]);
}
echo '</div>';
echo '</div>';
