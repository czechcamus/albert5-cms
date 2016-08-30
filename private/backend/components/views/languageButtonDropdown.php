<?php
use yii\bootstrap\ButtonDropdown;

/* @var string $label */
/* @var array $items */

echo ButtonDropdown::widget([
	'encodeLabel' => false,
	'label' => $label,
	'dropdown' => [
		'encodeLabels' => false,
		'items' => $items
	]
]);

