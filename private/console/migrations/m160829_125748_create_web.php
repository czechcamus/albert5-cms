<?php

use yii\db\Migration;

/**
 * Handles the creation for table `web`.
 */
class m160829_125748_create_web extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('web', [
            'id' => $this->primaryKey(),
            'title' => $this->string()->notNull(),
            'weburl' => $this->string()->notNull(),
            'active' => $this->boolean()->defaultValue(1),
            'main' => $this->boolean(),
            'public' => $this->boolean()->defaultValue(1),
            'theme' => $this->string()->notNull(),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('web');
    }
}
