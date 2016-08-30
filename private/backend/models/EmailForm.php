<?php
/**
 * Created by PhpStorm.
 * User: Pavel
 * Date: 20.9.2015
 * Time: 21:25
 */

namespace backend\models;


use common\models\LanguageRecord;
use common\models\EmailRecord;
use Yii;
use yii\base\Model;

class EmailForm extends Model
{
	/** @var integer actual page id */
	public $item_id;
	/** @var integer language id */
	public $language_id;
	/** @var string email od page */
	public $email;
	/** @var string hash of email and actual datetime */
	public $hash;
	/** @var array boxes of properties */
	public $boxes;

	const PROPERTY_ACTIVE = 1;

	/**
	 * @param integer|null $item_id
	 */
	public function __construct( $item_id = null ) {
		parent::__construct();
		if ($item_id) {
			/** @var $email EmailRecord */
			$email = EmailRecord::findOne($item_id);
			$this->item_id = $email->id;
			$this->email = $email->email;
			$this->language_id = $email->language_id;
			if ($email->active)
				$this->boxes[] = self::PROPERTY_ACTIVE;
		} else {
			$session = Yii::$app->session;
			if (!$session['language_id'])
				$session['language_id'] = LanguageRecord::getMainLanguageId();
			$this->language_id = $session['language_id'];
			$this->boxes[] = self::PROPERTY_ACTIVE;
		}
	}

	/**
	 * @inheritdoc
	 */
	public function rules() {
		return [
			['email','required', 'on' => ['create', 'update']],
			[['email'], 'string', 'max' => 255],
			[['item_id', 'language_id', 'boxes', 'hash'], 'safe']
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'email' => Yii::t('back', 'Email'),
			'boxes' => Yii::t('back', 'Properties'),
			'item_id' => Yii::t('back', 'Actual Item'),
			'language_id' => Yii::t('back', 'Language'),
			'hash' => Yii::t('back', 'Hash')
		];
	}

	/**
	 * Save EmailRecord model
	 * @param bool $insert
	 */
	public function saveEmail($insert = true) {
		$email = $insert ? new EmailRecord : EmailRecord::findOne($this->item_id);
		$email->attributes = $this->toArray();
		$email->active   = (is_array($this->boxes) && in_array(self::PROPERTY_ACTIVE, $this->boxes)) ? 1 : 0;
		if ($insert)
			$email->hash = $this->hashEmail();
		$email->save(false);
	}

	/**
	 * Deletes Email
	 * @throws \Exception
	 */
	public function deleteEmail() {
		/** @var $email EmailRecord */
		if ($email = EmailRecord::findOne($this->item_id))
			$email->delete();
	}

	/**
	 * Hashes email and salt and time
	 * @return string
	 */
	private function hashEmail() {
		return md5($this->email . 'poK&ohui#b546@54v' . time());
	}
}