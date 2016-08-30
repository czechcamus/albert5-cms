<?php

use yii\db\Migration;

/**
 * Handles the creation for table `menu_item_content`.
 * Has foreign keys to the tables:
 *
 * - `menu_item`
 * - `content`
 * - `category`
 */
class m160829_160541_create_menu_item_content extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('menu_item_content', [
            'id' => $this->primaryKey(),
            'menu_item_id' => $this->integer()->notNull(),
            'content_id' => $this->integer(),
            'category_id' => $this->integer(),
        ]);

        // creates index for column `menu_item_id`
        $this->createIndex(
            'idx-menu_item_content-menu_item_id',
            'menu_item_content',
            'menu_item_id'
        );

        // add foreign key for table `menu_item`
        $this->addForeignKey(
            'fk-menu_item_content-menu_item_id',
            'menu_item_content',
            'menu_item_id',
            'menu_item',
            'id',
            'CASCADE'
        );

        // creates index for column `content_id`
        $this->createIndex(
            'idx-menu_item_content-content_id',
            'menu_item_content',
            'content_id'
        );

        // add foreign key for table `content`
        $this->addForeignKey(
            'fk-menu_item_content-content_id',
            'menu_item_content',
            'content_id',
            'content',
            'id',
            'CASCADE'
        );

        // creates index for column `category_id`
        $this->createIndex(
            'idx-menu_item_content-category_id',
            'menu_item_content',
            'category_id'
        );

        // add foreign key for table `category`
        $this->addForeignKey(
            'fk-menu_item_content-category_id',
            'menu_item_content',
            'category_id',
            'category',
            'id',
            'CASCADE'
        );
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        // drops foreign key for table `menu_item`
        $this->dropForeignKey(
            'fk-menu_item_content-menu_item_id',
            'menu_item_content'
        );

        // drops index for column `menu_item_id`
        $this->dropIndex(
            'idx-menu_item_content-menu_item_id',
            'menu_item_content'
        );

        // drops foreign key for table `content`
        $this->dropForeignKey(
            'fk-menu_item_content-content_id',
            'menu_item_content'
        );

        // drops index for column `content_id`
        $this->dropIndex(
            'idx-menu_item_content-content_id',
            'menu_item_content'
        );

        // drops foreign key for table `category`
        $this->dropForeignKey(
            'fk-menu_item_content-category_id',
            'menu_item_content'
        );

        // drops index for column `category_id`
        $this->dropIndex(
            'idx-menu_item_content-category_id',
            'menu_item_content'
        );

        $this->dropTable('menu_item_content');
    }
}
