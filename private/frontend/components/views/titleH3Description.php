<?php
/* @var $item \common\models\ContentRecord */

if ($item) {
	if ($item->title) {
		echo '<h3>' . $item->title . '</h3>';
	}

	if ($item->description) {
		echo $item->description;
	}
}
