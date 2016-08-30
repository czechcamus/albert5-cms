<?php
/* @var $item \common\models\Article */
use yii\helpers\Html;

/* @var $articleType string */

if ($item) {
	echo '<div class="row ' . $articleType . '">';
		if ($item->image) {
			echo '<div class="col s12 m6">';
			$image = Html::img('@web/admin/' . Yii::$app->params['fileUploadDir'] . $item->image->filename, [
				'alt' => Yii::t('front', 'Image') . ($item->image->title ? ' - ' . $item->image->title : ''),
				'title' => $item->image->title ?: null,
				'class' => 'responsive-img'
			]);
			echo Html::a($image, ['site/content', 'id' => $item->id]);
			echo '</div>';
			echo '<div class="col s12 m6">';
		} else {
			echo '<div class="col s12">';
		}
		echo '<h3>' . Html::a($item->title, ['site/content', 'id' => $item->id]) . '</h3>';
		echo $item->perex;
		echo '</div>';
	echo '</div>';
}