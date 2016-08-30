<?php
use pavlinter\display\DisplayImage;

Yii::$container->set('pavlinter\display\DisplayImage', [
	'config' => [
		'all' => [
			'imagesWebDir' => '@web/admin/upload/images/',
			'imagesDir' => '@webroot/admin/upload/images/',
			'defaultWebDir' => '@web/admin/upload/default/',
			'defaultDir' => '@webroot/admin/upload/default/',
			'mode' => DisplayImage::MODE_OUTBOUND,
		],
	]
]);
