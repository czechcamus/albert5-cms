<?php
/* @var $items \common\models\LanguageRecord[] */
/* @var $dropdownId string */

use yii\helpers\Html;
use yii\helpers\Url;

if ($items) {
	echo '<ul id="' . $dropdownId . '" class="dropdown-content">';
	foreach ( $items as $item ) {
		echo '<li>' . Html::a( Html::img('@web/admin/images/flags/' . $item->acronym . '.gif',
				['alt' => Yii::t('front', 'flag') . ' ' . $item->acronym]) . ' ' . $item->title, Url::home() . ($item->main ? '' : $item->acronym)) . '</li>';
	}
	echo '</ul>';
}
