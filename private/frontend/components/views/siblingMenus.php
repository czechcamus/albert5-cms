<?php
/* @var $items frontend\models\MenuContent[] */
/* @var $parentMenuTitle string */
/* @var $title string */

use yii\helpers\Html;

if ($items) {
	echo '<div class="row">';
	echo '<div class="col s12">';
	echo '<div class="side-menu-items">';
	echo '<h3><span>' . $title . ' ' . $parentMenuTitle . '</span></h3>';
	echo '<ul>';
	foreach ( $items as $item ) {
		echo '<li class="menu-item-title">' . Html::a($item->title, $item->getUrl()) . ' <i class="material-icons tiny">navigate_next</i></li>';
	}
	echo '</ul>';
	echo '</div>';
	echo '</div>';
	echo '</div>';
}