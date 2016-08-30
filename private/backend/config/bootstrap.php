<?php
use pavlinter\display\DisplayImage;

Yii::$container->set('pavlinter\display\DisplayImage', [
	'config' => [
		'all' => [
			'imagesWebDir' => '@web/upload/images/',
			'imagesDir' => '@webroot/upload/images/',
			'defaultWebDir' => '@web/upload/default/',
			'defaultDir' => '@webroot/upload/default/',
			'mode' => DisplayImage::MODE_OUTBOUND,
		],
	]
]);
