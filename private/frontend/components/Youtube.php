<?php
/**
 * Created by PhpStorm.
 * User: Pavel
 * Date: 23.9.2015
 * Time: 21:49
 */

namespace frontend\components;


use yii\base\Widget;

/**
 * Class Youtube displayes youtube video
 * @package frontend\components
 */
class Youtube extends Widget
{
	public $id;

	public function run() {
		return $this->render('youtube', [
			'videoCode' => $this->id
		]);
	}
}