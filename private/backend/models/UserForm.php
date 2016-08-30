<?php
/**
 * Created by PhpStorm.
 * User: Pavel
 * Date: 31.1.2015
 * Time: 19:42
 */

namespace backend\models;


use common\models\UserRecord;
use Yii;
use yii\base\Model;

class UserForm extends Model
{
	public $username;
	public $email;
	public $password;
	public $role;
	public $status;

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			['username', 'filter', 'filter' => 'trim'],
			['username', 'required'],
			['username', 'unique', 'targetClass' => '\common\models\UserRecord', 'message' => Yii::t('back', 'This username has already been taken.'), 'on' => 'create'],
			['username', 'string', 'min' => 2, 'max' => 64],

			['email', 'filter', 'filter' => 'trim'],
			['email', 'required'],
			['email', 'email'],
			['email', 'unique', 'targetClass' => '\common\models\UserRecord', 'message' => Yii::t('back', 'This email address has already been taken.'), 'on' => 'create'],

			['password', 'required', 'on' => 'create'],
			['password', 'string', 'min' => 6],

			['role', 'required'],
			['role', 'string'],

			['status', 'required'],
			['status', 'integer']
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'username' => Yii::t('back', 'Username'),
			'password' => Yii::t('back', 'Password'),
			'status' => Yii::t('back', 'Status')
		];
	}

	/**
	 * @param bool|int $id
	 * @return bool
	 */
	public function saveUser($id = false)
	{
		if ($this->validate()) {
			if ($id) {
				if (!$user = UserRecord::findOne($id)){
					return false;
				}
			} else {
				$user= new UserRecord();
			}
			$user->username = $this->username;
			$user->email = $this->email;
			if ($this->password) {
				$user->setPassword($this->password);
				$user->generateAuthKey();
			}
			$user->status = $this->status;
			$user->save(false);

			// save role
			$auth = \Yii::$app->authManager;
			if ($id) {
				$roles = $auth->getRolesByUser($id);
				if (count($roles)) {
					$role = array_keys($roles)[0];
					$auth->revoke($roles[$role], $id);
				}
			}
			$role = $auth->getRole($this->role);
			$auth->assign($role, $user->getId());

			return true;
		}
		return false;
	}

	/**
	 * Changes status of user to deleted
	 * @param $id
	 * @return int
	 */
	public function deleteUser( $id ) {
		$rows = UserRecord::updateAll(['status' => UserRecord::STATUS_DELETED], ['id' => $id]);
		return $rows;
	}

	/**
	 * Gets role list for dropdown
	 * @return array
	 */
	public function getRoleList()
	{
		return [
			'member' => \Yii::t('back', 'Authenticated user'),
			'user' => \Yii::t('back', 'Editor'),
			'manager' => \Yii::t('back', 'Editor in chief'),
			'admin' => \Yii::t('back', 'Administrator')
		];
	}

	/**
	 * Gets status list for dropdown
	 * @return array
	 */
	public function getStatusList() {
		return [
			UserRecord::STATUS_ACTIVE => Yii::t('back', 'Active'),
			UserRecord::STATUS_INACTIVE =>  Yii::t('back', 'Inactive')
		];
	}

}