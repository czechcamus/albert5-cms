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

class InstallController extends Controller {
	public $defaultAction = 'make';

	public function actionMake() {
		// create necessary directories
		$this->createDirectories( str_replace( '\\', '/', __DIR__ ) . '/../../../web', [
				'assets',
				'admin/assets',
				'admin/upload',
				'admin/upload/files',
				'admin/upload/images',
			]
		);

		// database connection
		$pattern              = '/^[A-Za-z0-9\.|\-|_]*$/';
		$mainLocalFile        = \Yii::getAlias( '@common' ) . '/config/main-local.php';
		$mainLocalContent     = file_get_contents( $mainLocalFile );
		$dbname                                    = $this->prompt( 'Name of database: ', [
			'required' => true,
			'pattern'  => $pattern
		] );
		$mainLocalContent = preg_replace('/dbname=[a-z0-9\-_]*\'/', 'dbname=' . $dbname .'\'', $mainLocalContent);
		$username                                  = $this->prompt( 'Name of database user: ', [
			'required' => true,
			'pattern'  => $pattern
		] );
		$mainLocalContent = preg_replace('/username\'\s*=>\s*\'[a-z0-9\-_]*\'/', 'username\' => \'' . $username .'\'', $mainLocalContent);
		$password             = $this->prompt( 'Password of database user: ', [
			'required' => true,
			'pattern'  => $pattern
		] );
		$mainLocalContent = preg_replace('/password\'\s*=>\s*\'[a-z0-9\-_]*\'/', 'password\' => \'' . $password .'\'', $mainLocalContent);
		if ( !file_put_contents( $mainLocalFile, $mainLocalContent ) ) {
			throw new Exception( 'Updated configuration file not saved!' );
		}
	}

	private function createDirectories( $root, $directories ) {
		foreach ( $directories as $directory ) {
			echo "           ...create dir " . $root . '/' . $directory . "\n";
			@mkdir( $root . '/' . $directory );
		}
	}
}