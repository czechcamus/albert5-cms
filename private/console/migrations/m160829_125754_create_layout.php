<?php

use yii\db\Migration;

/**
 * Handles the creation for table `layout`.
 */
class m160829_125754_create_layout extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('layout', [
            'id' => $this->primaryKey(),
            'title' => $this->string()->notNull(),
            'filename' => $this->string(45)->notNull(),
            'active' => $this->boolean()->defaultValue(1),
            'main' => $this->boolean(),
            'content' => $this->smallInteger(1)->notNull(),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('layout');
    }
}
