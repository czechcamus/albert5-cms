<?php
/* @var $this \yii\web\View */
/* @var $invitationsItems common\models\ContentRecord[] */
/* @var $invitationsMenuUrlParts array */
/* @var $actualitiesItems common\models\ContentRecord[] */
/* @var $actualitiesMenuUrlParts array */
/* @var $itemsCount integer */
/* @var $columnsCount integer */
/* @var $wordsCount integer */
/* @var $maxImageSize array */

use frontend\models\ArticleContent;
use yii\helpers\ArrayHelper;
use yii\helpers\Inflector;

if ( $invitationsItems || $actualitiesItems ) {
	echo '<div class="section">';
	echo '<div class="container">';
	if ( $columnsCount > 3 ) {
		$columnsCount = 3;
	}
	$i = 0;
	foreach ( $invitationsItems as $item ) {
		$test = $i % $columnsCount;
		if ( $test == 0 ) {
			echo '<div class="row">';
		}
		$articleUrlParts = [
			'page/article',
			'ida'      => $item->id,
			'article'  => Inflector::slug( strip_tags( $item->title ) ),
			'web'      => \Yii::$app->request->get('web'),
			'language' => \Yii::$app->request->get('language')
		];
		if ( ! $invitationsMenuUrlParts ) {
			$invitationsMenuUrlParts = ArticleContent::getMenuUrlParts( $item->id );
		}
		$url = ArrayHelper::merge( $articleUrlParts, $invitationsMenuUrlParts );
		echo $this->renderFile( '@frontend/components/homepage/views/_invitationActuality.php', [
			'item'         => $item,
			'url'          => $url,
			'maxImageSize' => $maxImageSize,
			'wordsCount'   => $wordsCount,
			'columnsCount' => $columnsCount,
			'itemType'     => 'invitation'
		] );
		if ( $test == ( $columnsCount - 1 ) ) {
			echo '</div>';
		}
		++ $i;
	}
	foreach ( $actualitiesItems as $item ) {
		$test = $i % $columnsCount;
		if ( $test == 0 ) {
			echo '<div class="row">';
		}
		$articleUrlParts = [
			'page/article',
			'ida'      => $item->id,
			'article'  => Inflector::slug( strip_tags( $item->title ) ),
			'web'      => \Yii::$app->request->get('web'),
			'language' => \Yii::$app->request->get('language')
		];
		if ( ! $actualitiesMenuUrlParts ) {
			$actualitiesMenuUrlParts = ArticleContent::getMenuUrlParts( $item->id );
		}
		$url = ArrayHelper::merge( $articleUrlParts, $actualitiesMenuUrlParts );
		echo $this->renderFile( '@frontend/components/homepage/views/_invitationActuality.php', [
			'item'         => $item,
			'url'          => $url,
			'maxImageSize' => $maxImageSize,
			'wordsCount'   => $wordsCount,
			'columnsCount' => $columnsCount,
			'itemType'     => 'actuality'
		] );
		if ( $test == ( $columnsCount - 1 ) ) {
			echo '</div>';
		}
		++ $i;
	}
	if ( $test != ( $columnsCount - 1 ) ) {
		echo '</div>';
	}
	echo '</div>';
	echo '</div>';
}
