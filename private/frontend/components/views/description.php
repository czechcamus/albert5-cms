<?php
/* @var $item \common\models\ContentRecord */

if ($item && $item->description) {
	echo $item->description;
}
