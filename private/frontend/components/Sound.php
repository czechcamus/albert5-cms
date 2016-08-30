<?php
/**
 * Created by PhpStorm.
 * User: Pavel
 * Date: 23.9.2015
 * Time: 10:42
 */

namespace frontend\components;


use Yii;
use yii\base\InvalidParamException;
use yii\base\Widget;

/**
 * Class Sound displays sound player with given sound
 * @property $id integer is ID of playable
 * @package frontend\components
 */

class Sound extends Widget
{
	public $id;

	private $_sound;

	public function init() {
		parent::init();
		if ($this->id) {
			/** @var \common\models\Sound $sound */
			$sound = \common\models\Sound::findOne($this->id);
			if ($sound && ($sound->public || Yii::$app->user->can('member')))
				$this->_sound = $sound;

		} else {
			throw new InvalidParamException( Yii::t( 'front', 'No required parameter given') . ' - id');
		}
	}

	public function run() {
		return $this->render('sound', [
			'sound' => $this->_sound
		]);
	}
}