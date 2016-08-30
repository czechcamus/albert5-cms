<?php
/* @var $this yii\web\View */
/* @var $menuContent \frontend\models\MenuContent */
/* @var $page \common\models\Page */

use frontend\utilities\FrontEndHelper;
use pavlinter\display\DisplayImage;

if ( $page->image ) {
	echo DisplayImage::widget( [
		'options'  => [
			'class' => 'responsive-img',
			'title' => $menuContent->title
		],
		'category' => 'all',
		'image'    => $page->image->filename
	] );
}

if ( $page->perex ) {
	echo '<div class="perex">' . $page->perex . '</div>';
}

if ( $page->description ) {
	echo '<div class="description">';
	echo FrontEndHelper::parseContent( $page->description );
	echo '</div>';
}