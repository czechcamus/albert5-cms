<?php

use yii\db\Migration;

/**
 * Handles the creation for table `language`.
 */
class m160829_125752_create_language extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('language', [
            'id' => $this->primaryKey(),
            'title' => $this->string(20)->notNull(),
            'acronym' => $this->string(3)->notNull(),
            'active' => $this->boolean()->defaultValue(1),
            'main' => $this->boolean(),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('language');
    }
}
