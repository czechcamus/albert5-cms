<?php
/**
 * Created by PhpStorm.
 * User: Pavel
 * Date: 30.11.2015
 * Time: 15:58
 */

namespace backend\utilities;


use yii\helpers\Url;

class BackendHelper {

	/**
	 * Parses content for articles
	 * @param $content
	 * @return string
	 */
	public static function parseContent( $content ) {
		$baseUrl = Url::base('http') . '/';
		$decodedContent = html_entity_decode($content);
		// Absolute href
		$content = preg_replace(['/href=\"\/admin\//'], ['href="' . $baseUrl], $decodedContent);
		// Absolute src
		$content = preg_replace(['/src=\"\/admin\//'], ['src="' . $baseUrl], $content);
		// Image width & height
		$content = preg_replace(['/style="width:\s(\d+)px;\sheight:\s(\d+)px;\s/'], ['width="$1" height="$2" style="'], $content);
		// Article
		$content = preg_replace(['/<p>\[article=(\"\d+\")\sarticleType=(\"\w+\")\]<\/p>/'], ['</td></tr><?php echo \backend\components\NewsletterArticle::widget(["id" => $1, "type" => $2]); ?><tr><td>'], $content);

		return eval('?>' . $content);
	}

}