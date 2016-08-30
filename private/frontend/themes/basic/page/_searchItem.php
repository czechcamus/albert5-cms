<?php
/* @var $this yii\web\View */
/* @var $model array */

use yii\helpers\Html;

?>

<div class="row">
	<div class="col s12">
	<h4><?= Html::a($model['title'], $model['url']); ?></h4>
	<p><?= strip_tags($model['perex'], 'a, strong, b, em, i'); ?></p>
	</div>
</div>

