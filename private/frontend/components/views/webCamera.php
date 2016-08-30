<?php
/* @var $this yii\web\View */
/* @var $image array */

use yii\helpers\Html;

echo Html::img($image['filename'], [
	'id' => $image['id'],
	'alt' => $image['alt'],
	'class' => 'tooltipped responsive-img',
	'data' => [
		'position' => 'bottom',
		'delay' => '50',
		'tooltip' => $image['tooltip']
	]
]);


$this->registerJs(
	'var testImage = function() {
		var imageId = "#' . $image['id'] . '"; 
		var date = new Date();
		var numericTime = date.getTime();
		var stringTime = date.toLocaleString();
		var imageUrl = "' . Yii::$app->params['webCameraUrl'] . '" + numericTime;
		
		$(imageId).attr("src", imageUrl).attr("src", imageUrl).attr("alt", "' . \Yii::t('front', 'webcamera image') . '").attr("data-tooltip", "' . \Yii::t('front', 'webcamera image of pool') . ' - " + stringTime).error(function() {
			$(imageId).attr("src", "' . \Yii::$app->request->baseUrl . '/basic-assets/img/temp/webkamera.jpg").attr("alt", "' . \Yii::t('front', 'image of pool') . '").attr("data-tooltip", "' . \Yii::t('front', 'image of pool') . ' - " + stringTime); 								
		});		
	}
	
	setInterval(testImage, 10000);'
);
