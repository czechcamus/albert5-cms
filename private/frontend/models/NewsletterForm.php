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
use yii\helpers\Url;

class NewsletterForm extends Model
{
	public $agree;
	public $email;

	/**
	 * @inheritdoc
	 */
	public function rules() {
		return [
			[['agree', 'email'], 'required'],
			['agree', 'boolean'],
			['email', 'email']
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'agree' => Yii::t('front', 'Agree with sending of newsletter'),
			'email' => Yii::t('front', 'Your email'),
		];
	}

	public function send( $subscribe, $hashedEmail ) {
		if ($subscribe) {
			$body = Yii::t('front', 'Thank you for subscription of our newsletter.') . "\n\n";
			$body .= Yii::t('front', 'To confirm please click this link') . ': ' . Url::to(['site/newsletter-subscribe-confirm', 'c' => $hashedEmail], true);
		} else {
			$body = Yii::t('front', 'We regret that you unsubscribe our newsletter.') . "\n\n";
			$body .= Yii::t('front', 'To confirm please click this link') . ': ' . Url::to(['site/newsletter-unsubscribe-confirm', 'c' => $hashedEmail], true);
		}

		$sendingEmail = Yii::$app->params['sendingEmail'];
		Yii::$app->mailer->compose()
	         ->setTo($this->email)
	         ->setFrom([$sendingEmail => Yii::$app->params['sendingEmailTitle']])
	         ->setSubject($subscribe ? Yii::t('front', 'Newsletter subscribe') : Yii::t('front', 'Newsletter unsubscribe'))
	         ->setTextBody($body)
	         ->send();

		return true;
	}
}