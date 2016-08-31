<?php
/**
 * Created by PhpStorm.
 * User: Pavel
 * Date: 31.8.2016
 * Time: 13:19
 */

namespace frontend\modules\install\models;


use Exception;
use yii\base\Model;

class InstallForm extends Model
{
	/**
	 * Updates config file
	 * @param $configFile
	 * @param $items
	 *
	 * @throws Exception
	 */
	protected function setConfig($configFile, $items) {
		$configFileContent     = file_get_contents( $configFile );
		foreach ( $items as $item ) {
			$configFileContent = preg_replace('/' . $item . '\'\s*=>\s*\'[\w\s\-:_\.,;@=]*\'/u', $item . '\' => \'' . $this->$item .'\'', $configFileContent);
		}
		if ( !file_put_contents( $configFile, $configFileContent ) ) {
			throw new Exception( 'Updated configuration file not saved!' );
		}
	}
}