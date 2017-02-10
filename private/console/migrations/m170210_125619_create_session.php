<?php

use yii\db\Migration;

/**
 * Handles the creation for table `session`.
 */
class m170210_125619_create_session extends Migration
{
	/**
	 * @inheritdoc
	 */
	public function up()
	{
		$tableOptions = null;
		if ($this->db->driverName === 'mysql') {
			// http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
			$tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
		}

		$this->createTable('{{%session}}', [
			'id' => $this->string()->notNull(),
			'expire' => $this->integer(),
			'data' => $this->binary(),
			'user_id' => $this->integer(),
			'last_write' => $this->integer(),
			'PRIMARY KEY ([[id]])',
		], $tableOptions);
	}

	/**
	 * @inheritdoc
	 */
	public function down()
	{
		$this->dropTable('{{%session}}');
	}
}
