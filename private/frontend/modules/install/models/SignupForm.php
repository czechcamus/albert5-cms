<?php
namespace frontend\modules\install\models;

use common\models\UserRecord;
use frontend\modules\install\Module;
use yii\base\Model;

/**
 * Signup form
 */
class SignupForm extends Model {
	public $username;
	public $email;
	public $password;

	/**
	 * @inheritdoc
	 */
	public function rules() {
		return [
			[ 'username', 'filter', 'filter' => 'trim' ],
			[ 'username', 'required' ],
			[
				'username',
				'unique',
				'targetClass' => '\common\models\UserRecord',
				'message'     => 'This username has already been taken.'
			],
			[ 'username', 'string', 'min' => 2, 'max' => 255 ],

			[ 'email', 'filter', 'filter' => 'trim' ],
			[ 'email', 'required' ],
			[ 'email', 'email' ],
			[
				'email',
				'unique',
				'targetClass' => '\common\models\UserRecord',
				'message'     => 'This email address has already been taken.'
			],

			[ 'password', 'required' ],
			[ 'password', 'string', 'min' => 6 ],
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels() {
		return [
			'username' => Module::t( 'inst', 'username' ),
			'email'    => Module::t( 'inst', 'email' ),
			'password' => Module::t( 'inst', 'password' ),
		];
	}

	/**
	 * Signs user up.
	 *
	 * @return UserRecord|null the saved model or null if saving fails
	 */
	public function signup() {
		if ( $this->validate() ) {
			$user           = new UserRecord();
			$user->username = $this->username;
			$user->email    = $this->email;
			$user->setPassword( $this->password );
			$user->generateAuthKey();
			if ( $user->save() ) {
				$auth  = \Yii::$app->authManager;
				$roles = $auth->getRolesByUser( $user->id );
				if ( count( $roles ) ) {
					$role = array_keys( $roles )[0];
					$auth->revoke( $roles[ $role ], $user->id );
				}
				$role = $auth->getRole( 'admin' );
				$auth->assign( $role, $user->id );

				return $user;
			}
		}

		return null;
	}
}
