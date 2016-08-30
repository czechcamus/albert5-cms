<?php
/**
 * Created by PhpStorm.
 * User: Pavel
 * Date: 4.9.2015
 * Time: 21:03
 */

namespace frontend\utilities;


use common\models\LanguageRecord;
use common\models\MenuItemRecord;
use common\models\MenuRecord;
use common\models\WebRecord;
use Yii;
use yii\helpers\Url;

class FrontEndHelper {

	/**
	 * Returns menu id from given text id
	 * @param $textId
	 * @return bool|string
	 */
	public static function getMenuIdFromTextId( $textId ) {
		$id = MenuRecord::find()->select('id')->where([
			'web_id' => self::getWebIdFromTextId(\Yii::$app->request->get('web', \Yii::$app->params['defaultWeb'])),
			'text_id' => $textId
		])->scalar();
		return $id;
	}

	/**
	 * Returns web id from given web text id
	 * @param $textId
	 * @return bool|string
	 */
	public static function getWebIdFromTextId( $textId ) {
		$id = WebRecord::find()->select('id')->where([
			'weburl' => $textId
		])->scalar();
		return $id;
	}

	/**
	 * Returns main menu item id for given web and language
	 * @param $webId
	 * @param $languageId
	 * @return bool|string
	 */
	public static function getMainMenuItemId( $webId, $languageId ) {
		$mainMenuId = MenuRecord::find()->select('id')->where([
			'web_id' => $webId,
			'main' => 1
		])->scalar();
		if ($mainMenuId) {
			$mainMenuItemId = MenuItemRecord::find()->select('id')->where([
				'menu_id' => $mainMenuId,
				'language_id' => $languageId,
				'main' => 1
			])->scalar();
			if ($mainMenuItemId) {
				return $mainMenuItemId;
			}
		}
		return false;
	}

	/**
	 * Returns language id from application language property
	 * @return bool|string
	 */
	public static function getLanguageIdFromAcronym() {
		$id = LanguageRecord::find()->select('id')->where([
			'acronym' => \Yii::$app->language
		])->scalar();
		return $id;
	}

	/**
	 * Returns css font-size property in %
	 * @param int $frequency
	 * @param int $step
	 * @return string
	 */
	public static function getTagItemFontSize( $frequency, $step = 0 ) {
		if (!$step) $step = \Yii::$app->params['defaultTagItemSizeStep'];
		$multiplicator = ($test = floor(($frequency - 1) / $step)) > 10 ? 10 : $test;
		return 'font-size: ' . (100 + (10 * $multiplicator)) . '%;';
	}

	/**
	 * Parses content for gallery, sound, youtube video and polls
	 * @param $content
	 * @return string
	 */
	public static function parseContent( $content ) {
		$decodedContent = html_entity_decode($content);
		// Gallery
		$content = preg_replace(['/\[gallery=(\"\d+\")\stype=(\"\w+\")\salign=(\"\w+\")\]/'], ['<?php echo \frontend\components\Gallery::widget(["id" => $1, "type" => $2, "align" => $3]); ?>'], $decodedContent);
		// Sound
		$content = preg_replace(['/\[sound=(\"\d+\")\]/'], ['<?php echo \frontend\components\Sound::widget(["id" => $1]); ?>'], $content);
		// Youtube
		$content = preg_replace(['/\[youtube=(\"\w+\")\]/'], ['<?php echo \frontend\components\Youtube::widget(["id" => $1]); ?>'], $content);
		// Poll
		$content = preg_replace(['/\[poll=(\"\d+\")\schartType=(\"\w+\")\scolWidth=(\"\w+\")\]/'], ['<?php echo \frontend\components\Poll::widget(["id" => $1, "chartType" => $2, "colWidth" => $3]); ?>'], $content);

		return eval('?>' . $content);
	}

	/**
	 * Parses PDF content for gallery, sound, youtube video and polls
	 * @param $content
	 * @return string
	 */
	public static function parsePdfContent( $content ) {
		$decodedContent = html_entity_decode($content);
		// Gallery
		$content = preg_replace(['/\[gallery=(\"\d+\")\stype=(\"\w+\")\salign=(\"\w+\")\]/'], ['<?php echo \frontend\components\pdf\Gallery::widget(["id" => $1, "type" => $2, "align" => $3]); ?>'], $decodedContent);
		// Sound
		$content = preg_replace(['/\[sound=(\"\d+\")\]/'], [''], $content);
		// Youtube
		$content = preg_replace(['/\[youtube=(\"\w+\")\]/'], [''], $content);
		// Poll
		$content = preg_replace(['/\[poll=(\"\d+\")\schartType=(\"\w+\")\scolWidth=(\"\w+\")\]/'], [''], $content);

		return eval('?>' . $content);
	}

	/**
	 * Parses newsletter content for newsletter articles
	 * @param $content
	 * @return string
	 */
	public static function parseNewsletter( $content ) {
		$decodedContent = html_entity_decode($content);
		$content = preg_replace(['/\[article=(\"\d+\")\sarticleType=(\"\w+\")\]/'], ['<?php echo \frontend\components\NewsletterArticle::widget(["id" => $1, "articleType" => $2]); ?>'], $decodedContent);

		return eval('?>' . $content);
	}

	/**
	 * Returns previous url
	 * @return string
	 */
	public static function previousUrl() {
		return (Yii::$app->request->get('c') ? Url::previous(Yii::$app->request->get('c')) : Url::previous());
	}
}