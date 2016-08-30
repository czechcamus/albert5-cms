<?php
/* @var $this yii\web\View */
/* @var $url string */

use yii\helpers\Html;
use yii\helpers\Url;

?>
<div class="buttons">
	<?= Html::a('<i class="material-icons">print</i>',  Url::current(['type' => 'pdf'], true), [
		'class' => 'btn-floating waves-effect waves-light printer',
		'title' => Yii::t( 'front', 'Print to PDF' )
	]); ?>
	<?= Html::a('<i class="pe-so-twitter"></i>', 'https://twitter.com/intent/tweet?url=' . $url, [
		'class' => 'btn-floating waves-effect waves-light sicon twitter',
		'title' => Yii::t( 'front', 'Share on twitter' )
	]); ?>
	<?= Html::a('<i class="pe-so-facebook"></i>', 'http://www.facebook.com/sharer.php?u=' . $url, [
		'class' => 'btn-floating waves-effect waves-light sicon facebook',
		'title' => Yii::t( 'front', 'Share on facebook' )
	]); ?>
	<?= Html::a('<i class="pe-so-google-plus"></i>', 'https://plus.google.com/share?url=' . $url, [
		'class' => 'btn-floating waves-effect waves-light sicon google',
		'title' => Yii::t( 'front', 'Share on google+' )
	]); ?>
</div>

