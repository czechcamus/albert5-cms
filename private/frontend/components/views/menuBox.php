<?php
/* @var $items frontend\models\MenuContent[] */
/* @var $title string */

use yii\helpers\Html;

if ($items) {
	echo '<div class="row">';
	echo '<div class="col s12">';
	echo '<div class="side-menu-items">';
	echo '<h3><span>' . $title . '</span></h3>';
	echo '<ul>';
	foreach ( $items as $item ) {
		$pos = strpos($item->title, ';');
		if ($pos === false) {
			$itemTitle = $item->title;
		} else {
			list($itemTitle,$icon,$subtitle) = explode(';', $item->title);
		}
		echo '<li class="menu-item-title">' . Html::a($itemTitle, $item->getUrl()) . ' <i class="material-icons tiny">navigate_next</i></li>';
	}
	echo '</ul>';
	echo '</div>';
	echo '</div>';
	echo '</div>';
}