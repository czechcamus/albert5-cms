<?php
/* @var $this yii\web\View */
/* @var $items common\models\ContentRecord|common\models\CategoryRecord|common\models\LayoutRecord|common\models\MenuRecord|common\models\MenuItemRecord */
/* @var $itemsOptions array */

if ($items) {
	if ($itemsOptions['prompt']) {
		echo "<option value=''>" . $itemsOptions['prompt'] . "</option>";
	}
	if ($itemsOptions['arr']) {
		foreach ( $items as $key => $value ) {
			echo "<option value='$key'>$value</option>";
		}

	} else {
		foreach ( $items as $item ) {
			echo "<option value='$item->id'>$item->title</option>";
		}
	}
}
