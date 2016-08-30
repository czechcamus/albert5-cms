<?php
/**
 * Created by PhpStorm.
 * User: Pavel
 * Date: 29.8.2016
 * Time: 8:26
 */

namespace console\controllers;


use yii\base\Exception;
use yii\console\Controller;

class InstallController extends Controller
{
	public $defaultAction = 'make';

	public function actionMake() {
		// database connection
		$pattern = '/^[A-Za-z0-9\.|\-|_]*$/';
		$mainLocalFile = \Yii::getAlias('@common') . '/config/main-local.php';
		$mainLocalDefaultFile = \Yii::getAlias('@common') . '/config/main-local-default.php';
		$mainLocalContent = file_get_contents($mainLocalDefaultFile);
		$dbname = $this->prompt('Name of database: ', [
			'required' => true,
			'pattern' => $pattern
		]);
		$mainLocalContent = str_replace('database_name', $dbname, $mainLocalContent);
		$username = $this->prompt('Name of database user: ', [
			'required' => true,
			'pattern' => $pattern
		]);
		$mainLocalContent = str_replace('database_user', $username, $mainLocalContent);
		$password = $this->prompt('Password of database user: ', [
			'required' => true,
			'pattern' => $pattern
		]);
		$mainLocalContent = str_replace('user_password', $password, $mainLocalContent);
		if (!file_put_contents($mainLocalFile, $mainLocalContent)) {
			throw new Exception('Updated configuration file not saved!');
		}
	}
}