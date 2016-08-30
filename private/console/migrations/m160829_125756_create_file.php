<?php

use yii\db\Migration;

/**
 * Handles the creation for table `file`.
 */
class m160829_125756_create_file extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('file', [
            'id' => $this->primaryKey(),
            'title' => $this->string(),
            'filename' => $this->string()->notNull(),
            'type' => $this->smallInteger(6),
            'public' => $this->boolean()->defaultValue(1),
            'file_time' => $this->integer(),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('file');
    }
}
