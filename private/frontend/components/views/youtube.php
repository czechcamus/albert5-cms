<?php
/* @var $videoCode string */

if ($videoCode) {
	echo '<div class="video-container">';
	echo '<iframe src="//www.youtube.com/embed/' . $videoCode . '?rel=0" frameborder="0" allowfullscreen></iframe>';
	echo '</div>';
}