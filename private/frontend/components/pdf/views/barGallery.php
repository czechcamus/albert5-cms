<?php
/* @var $gallery \common\models\Gallery */
/* @var $images array */

if ($gallery) {
	echo '<div class="gallery-bar">';
	echo '<h3>' . $gallery->title . '</h3>';
	if ($images) {
		foreach ( $images as $image ) {
			echo '<div class="image-box"><div>';
			echo $image;
			echo '</div></div>';
		}
	}
	echo '</div>';
}