<?php

use yii\db\Migration;

/**
 * Handles the creation for table `menu`.
 * Has foreign keys to the tables:
 *
 * - `web`
 */
class m160829_125750_create_menu extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('menu', [
            'id' => $this->primaryKey(),
            'title' => $this->string()->notNull(),
            'text_id' => $this->string()->notNull(),
            'web_id' => $this->integer()->notNull(),
            'active' => $this->boolean()->defaultValue(1),
            'main' => $this->boolean(),
            'public' => $this->boolean()->defaultValue(1),
        ]);

        // creates index for column `web_id`
        $this->createIndex(
            'idx-menu-web_id',
            'menu',
            'web_id'
        );

        // add foreign key for table `web`
        $this->addForeignKey(
            'fk-menu-web_id',
            'menu',
            'web_id',
            'web',
            'id',
            'CASCADE'
        );
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        // drops foreign key for table `web`
        $this->dropForeignKey(
            'fk-menu-web_id',
            'menu'
        );

        // drops index for column `web_id`
        $this->dropIndex(
            'idx-menu-web_id',
            'menu'
        );

        $this->dropTable('menu');
    }
}
