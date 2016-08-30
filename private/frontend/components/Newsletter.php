<?php
/**
 * Created by PhpStorm.
 * User: Pavel
 * Date: 12.10.2015
 * Time: 17:25
 */

namespace frontend\components;


use common\models\EmailRecord;
use frontend\models\NewsletterForm;
use frontend\utilities\FrontEndHelper;
use yii\base\Widget;
use yii\db\Expression;

class Newsletter extends Widget
{
	public function init() {
		parent::init();
	}

	public function run() {
		$model = new NewsletterForm();
		if ($model->load(\Yii::$app->request->post()) && $model->validate()) {
			$subscribe = \Yii::$app->request->post('subscribe-button');
			if (isset($subscribe)) {
				if ($model->agree == 0) {
					$model->addError('agree', \Yii::t('front', 'You must agree with newsletter subscription.'));
				} else {
					/** @var EmailRecord $email */
					$email = EmailRecord::findOne(['email' => $model->email]);
					if ($email && ($email->active == 1)) {
						$model->addError('email', \Yii::t('front', 'This email is already in use.'));
					} else {
						if (!$email) {
							$email = new EmailRecord();
						}
						$hashedEmail = $this->hashEmail($model->email);
						$email->email = $model->email;
						$email->hash = $hashedEmail;
						$email->active = 0;
						$email->created_at = new Expression('NOW()');
						$email->language_id = FrontEndHelper::getLanguageIdFromAcronym();
						$email->save();
						$model->send(true, $hashedEmail);
						\Yii::$app->session->setFlash('info', \Yii::t('front', 'Thank you for subscription request of our newsletter.'));
					}
				}
			} else {
				/** @var EmailRecord $email */
				$email = EmailRecord::findOne(['email' => $model->email]);
				if (!$email || ($email && $email->active == 0)) {
					$model->addError('email', \Yii::t('front', 'Email not found.'));
				} else {
					$hashedEmail = $this->hashEmail($model->email);
					$email->hash = $hashedEmail;
					$email->save();
					$model->send(false, $hashedEmail);
					\Yii::$app->session->setFlash('newsletter-info', \Yii::t('front', 'Your unsubscription request was sent.'));
				}
			}
		}
		return $this->render('newsletter', compact('model'));
	}

	private function hashEmail( $email ) {
		return md5(time() . $email);
	}
}