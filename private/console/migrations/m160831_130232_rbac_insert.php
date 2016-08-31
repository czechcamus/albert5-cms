<?php

use yii\base\InvalidConfigException;
use yii\db\Migration;
use yii\rbac\DbManager;

class m160831_130232_rbac_insert extends Migration
{
	/**
	 * @throws yii\base\InvalidConfigException
	 * @return DbManager
	 */
	protected function getAuthManager()
	{
		$authManager = Yii::$app->getAuthManager();
		if (!$authManager instanceof DbManager) {
			throw new InvalidConfigException('You should configure "authManager" component to use database before executing this migration.');
		}
		return $authManager;
	}

	public function up()
    {
	    $authManager = $this->getAuthManager();
	    $this->db = $authManager->db;

	    $this->insert($authManager->itemTable, [
	    	'name' => 'admin',
		    'type' => 1,
		    'description' => 'Admin can do anything',
		    'created_at' => time(),
		    'updated_at' => time()
	    ]);
	    $this->insert($authManager->itemTable, [
	    	'name' => 'guest',
		    'type' => 1,
		    'description' => 'nobody',
		    'created_at' => time(),
		    'updated_at' => time()
	    ]);
	    $this->insert($authManager->itemTable, [
	    	'name' => 'manager',
		    'type' => 1,
		    'description' => 'Manager can add content, categories, menu items and users',
		    'created_at' => time(),
		    'updated_at' => time()
	    ]);
	    $this->insert($authManager->itemTable, [
	    	'name' => 'member',
		    'type' => 1,
		    'description' => 'Authenticated user',
		    'created_at' => time(),
		    'updated_at' => time()
	    ]);
	    $this->insert($authManager->itemTable, [
	    	'name' => 'user',
		    'type' => 1,
		    'description' => 'User can add only content',
		    'created_at' => time(),
		    'updated_at' => time()
	    ]);
	    $this->insert($authManager->itemChildTable, [
	    	'parent' => 'member',
		    'child' => 'guest'
	    ]);
	    $this->insert($authManager->itemChildTable, [
	    	'parent' => 'admin',
		    'child' => 'manager'
	    ]);
	    $this->insert($authManager->itemChildTable, [
	    	'parent' => 'user',
		    'child' => 'member'
	    ]);
	    $this->insert($authManager->itemChildTable, [
	    	'parent' => 'manager',
		    'child' => 'user'
	    ]);
    }

    public function down()
    {
	    $authManager = $this->getAuthManager();
	    $this->db = $authManager->db;

    	$this->truncateTable($authManager->itemChildTable);
    	$this->truncateTable($authManager->itemTable);
    }
}
