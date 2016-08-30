<?php
/* @var $gallery \common\models\Gallery */
/* @var $image string */

if ($gallery) {
	echo '<div class="gallery-box">';
	echo '<h3>' . $gallery->title . '</h3>';
	echo $image;
	echo '</div>';
}