<?php
/* @var $sound \common\models\Sound */

use xj\jplayer\AudioWidget;

if ($sound) {
	$fileExt = strtolower(array_pop(explode('.', $sound->filename)));
	echo AudioWidget::widget([
		'mediaOptions' => [
			'title' => $sound->title ? $sound->title : Yii::t('front', 'Unknown sound file'),
			$fileExt => Yii::getAlias('@web/admin/') . Yii::$app->params['fileUploadDir'] . $sound->filename
		]
	]);
}