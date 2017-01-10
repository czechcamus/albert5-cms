<?php
/* @var $items common\models\ContentRecord[] */
/* @var $menuUrlParts array */
/* @var $title string */

use frontend\models\ArticleContent;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Inflector;

if ($items) {
	echo '<div class="row">';
	echo '<div class="col s12">';
	echo '<div class="side-menu-items">';
	echo '<h3><span>' . $title . '</span></h3>';
	echo '<ul>';
	foreach ( $items as $item ) {
		$articleUrlParts= [
			'page/article',
			'ida' => $item->id,
			'article' => Inflector::slug(strip_tags($item->title)),
			'web' => \Yii::$app->request->get('web'),
			'language' => \Yii::$app->request->get('language')
		];
		if (!$menuUrlParts) {
			$menuUrlParts = ArticleContent::getMenuUrlParts($item->id);
		}
		echo '<li class="menu-item-title">' . Html::a($item->title, ArrayHelper::merge($articleUrlParts, $menuUrlParts)) . ' <i class="material-icons">navigate_next</i></li>';
	}
	echo '</ul>';
	echo '</div>';
	echo '</div>';
	echo '</div>';
}