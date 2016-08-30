<?php
/**
 * Created by PhpStorm.
 * User: Pavel
 * Date: 1.10.2015
 * Time: 20:17
 */

namespace frontend\models;


use Yii;
use yii\base\Model;

class ContactForm extends Model
{
	public $name;
	public $email;
	public $message;
	public $verifyCode;

	/**
	 * @inheritdoc
	 */
	public function rules() {
		return [
			[['name', 'email', 'message'], 'required'],
			['name', 'string', 'max' => 255],
			['email', 'email'],
			['verifyCode', 'captcha']
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'name' => Yii::t('front', 'Your full name'),
			'email' => Yii::t('front', 'Your email'),
			'message' => Yii::t('front', 'Your message'),
			'verifyCode' => Yii::t('front', 'Verify code')
		];
	}

	/**
	 * Sends an email to the specified email address using the information collected by this model.
	 * @param  string  $email the target email address
	 * @return boolean whether the model passes validation
	 */
	public function send($email)
	{
		if ($this->validate()) {
			$body = Yii::t('front', 'Pretender name') . ": " . $this->name . "\n";
			$body .= Yii::t('front', 'Pretender email') . ": " . $this->email . "\n";
			$body .= Yii::t('front', 'Pretender message') . ": " . $this->message;

			Yii::$app->mailer->compose()
	             ->setTo($email)
	             ->setFrom([$this->email => $this->name])
	             ->setSubject(Yii::t('front', 'Message from site www.dasport.cz'))
	             ->setTextBody($body)
	             ->send();

			return true;
		} else {
			return false;
		}
	}

}