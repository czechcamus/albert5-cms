<?php
/* @var $this yii\web\View */
/* @var $content \frontend\models\MenuContent */
/* @var $page \common\models\Page */

use dosamigos\google\maps\LatLng;
use dosamigos\google\maps\overlays\Marker;
use frontend\utilities\FrontEndHelper;
use frontend\widgets\GoogleMap;
use pavlinter\display\DisplayImage;
use yii\helpers\Html;

$page = $content->content;

$data = [
	['lat' => 49.083191, 'lng' => 15.422774]
];

$center = new LatLng([
	'lat' => 49.083191,
	'lng' => 15.422774
]);

$map = new GoogleMap([
	'width' => 640,
	'height' => 400,
	'center' => $center,
	'zoom' => 16
]);

foreach ( $data as $item ) {
	$coord = new LatLng([
		'lat' => $item['lat'],
		'lng' => $item['lng']
	]);
	$marker = new Marker([
		'position' => $coord
	]);
	$map->addOverlay($marker);
}

echo '<body>';

echo '<h1>' . $content->title . '</h1>';

echo '<div class="row"><div class="col s12">';
echo Html::img($map->getStaticMapUrl());
echo '</div></div>';

if ($page->image) {
	echo DisplayImage::widget([
		'category' => 'all',
		'image' => $page->image->filename
	]);
}

if ($page->perex) {
	echo '<div class="perex">' . $page->perex . '</div>';
}

if ($page->description) {
	echo '<div class="description">';
	echo FrontEndHelper::parsePdfContent($page->description);
	echo '</div>';
}

echo '</body>';