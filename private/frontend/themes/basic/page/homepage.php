<?php
/* @var $this yii\web\View */
/* @var $content string */

use frontend\assets\basic\HomeAsset;
use frontend\utilities\FrontEndHelper;
use pavlinter\display\DisplayImage;

HomeAsset::register( $this );

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