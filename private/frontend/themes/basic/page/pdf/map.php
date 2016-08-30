<?php
/* @var $this yii\web\View */
/* @var $content \frontend\models\MenuContent */
/* @var $page \common\models\Page */

use dosamigos\google\maps\LatLng;
use dosamigos\google\maps\overlays\Marker;
use frontend\utilities\FrontEndHelper;
use frontend\widgets\GoogleMap;
use yii\helpers\Html;

$page = $content->content;

$data = [
	['lat' => 49.083191, 'lng' => 15.422774],
	['lat' => 49.082224, 'lng' => 15.433216],
	['lat' => 49.084096, 'lng' => 15.434793],
	['lat' => 49.081995, 'lng' => 15.433613],
	['lat' => 49.083391, 'lng' => 15.426961],
	['lat' => 49.082153, 'lng' => 15.434847],
	['lat' => 49.082818, 'lng' => 15.423321],
	['lat' => 49.080432, 'lng' => 15.419617],
	['lat' => 49.079634, 'lng' => 15.441433],
	['lat' => 49.074403, 'lng' => 15.442875],
	['lat' => 49.077979, 'lng' => 15.430677],
	['lat' => 49.084297, 'lng' => 15.478046],
	['lat' => 49.074553, 'lng' => 15.460972],
	['lat' => 49.093647, 'lng' => 15.395012],
	['lat' => 49.081998, 'lng' => 15.383993],
	['lat' => 49.059376, 'lng' => 15.439910],
	['lat' => 49.051084, 'lng' => 15.462888],
	['lat' => 49.086869, 'lng' => 15.357986],
	['lat' => 49.107628, 'lng' => 15.458102],
	['lat' => 49.123202, 'lng' => 15.412280],
	['lat' => 49.119018, 'lng' => 15.450244],
];

$center = new LatLng([
	'lat' => 49.082224,
	'lng' => 15.433216
]);
$map = new GoogleMap([
	'width' => 640,
	'height' => 800,
	'center' => $center,
	'zoom' => 13
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
	echo \pavlinter\display\DisplayImage::widget([
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