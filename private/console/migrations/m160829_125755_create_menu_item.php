<?php

use yii\db\Migration;

/**
 * Handles the creation for table `menu_item`.
 * Has foreign keys to the tables:
 *
 * - `language`
 * - `menu`
 * - `user`
 * - `user`
 * - `layout`
 * - `menu_item`
 */
class m160829_125755_create_menu_item extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('menu_item', [
            'id' => $this->primaryKey(),
            'title' => $this->string()->notNull(),
            'language_id' => $this->integer()->notNull(),
            'menu_id' => $this->integer()->notNull(),
            'active' => $this->boolean()->defaultValue(1),
            'main' => $this->boolean(),
            'public' => $this->boolean()->defaultValue(1),
            'created_at' => $this->dateTime(),
            'created_by' => $this->integer(),
            'updated_at' => $this->dateTime(),
            'updated_by' => $this->integer(),
            'item_order' => $this->smallInteger(6)->notNull(),
            'layout_id' => $this->integer(),
            'content_type' => $this->smallInteger(6)->notNull(),
            'parent_id' => $this->integer(),
            'link_url' => $this->string(),
            'link_target' => $this->integer(),
        ]);

        // creates index for column `language_id`
        $this->createIndex(
            'idx-menu_item-language_id',
            'menu_item',
            'language_id'
        );

        // add foreign key for table `language`
        $this->addForeignKey(
            'fk-menu_item-language_id',
            'menu_item',
            'language_id',
            'language',
            'id',
            'CASCADE'
        );

        // creates index for column `menu_id`
        $this->createIndex(
            'idx-menu_item-menu_id',
            'menu_item',
            'menu_id'
        );

        // add foreign key for table `menu`
        $this->addForeignKey(
            'fk-menu_item-menu_id',
            'menu_item',
            'menu_id',
            'menu',
            'id',
            'CASCADE'
        );

        // creates index for column `created_by`
        $this->createIndex(
            'idx-menu_item-created_by',
            'menu_item',
            'created_by'
        );

        // add foreign key for table `user`
        $this->addForeignKey(
            'fk-menu_item-created_by',
            'menu_item',
            'created_by',
            'user',
            'id',
            'CASCADE'
        );

        // creates index for column `updated_by`
        $this->createIndex(
            'idx-menu_item-updated_by',
            'menu_item',
            'updated_by'
        );

        // add foreign key for table `user`
        $this->addForeignKey(
            'fk-menu_item-updated_by',
            'menu_item',
            'updated_by',
            'user',
            'id',
            'CASCADE'
        );

        // creates index for column `layout_id`
        $this->createIndex(
            'idx-menu_item-layout_id',
            'menu_item',
            'layout_id'
        );

        // add foreign key for table `layout`
        $this->addForeignKey(
            'fk-menu_item-layout_id',
            'menu_item',
            'layout_id',
            'layout',
            'id',
            'CASCADE'
        );

        // creates index for column `parent_id`
        $this->createIndex(
            'idx-menu_item-parent_id',
            'menu_item',
            'parent_id'
        );

        // add foreign key for table `menu_item`
        $this->addForeignKey(
            'fk-menu_item-parent_id',
            'menu_item',
            'parent_id',
            'menu_item',
            'id',
            'CASCADE'
        );
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        // drops foreign key for table `language`
        $this->dropForeignKey(
            'fk-menu_item-language_id',
            'menu_item'
        );

        // drops index for column `language_id`
        $this->dropIndex(
            'idx-menu_item-language_id',
            'menu_item'
        );

        // drops foreign key for table `menu`
        $this->dropForeignKey(
            'fk-menu_item-menu_id',
            'menu_item'
        );

        // drops index for column `menu_id`
        $this->dropIndex(
            'idx-menu_item-menu_id',
            'menu_item'
        );

        // drops foreign key for table `user`
        $this->dropForeignKey(
            'fk-menu_item-created_by',
            'menu_item'
        );

        // drops index for column `created_by`
        $this->dropIndex(
            'idx-menu_item-created_by',
            'menu_item'
        );

        // drops foreign key for table `user`
        $this->dropForeignKey(
            'fk-menu_item-updated_by',
            'menu_item'
        );

        // drops index for column `updated_by`
        $this->dropIndex(
            'idx-menu_item-updated_by',
            'menu_item'
        );

        // drops foreign key for table `layout`
        $this->dropForeignKey(
            'fk-menu_item-layout_id',
            'menu_item'
        );

        // drops index for column `layout_id`
        $this->dropIndex(
            'idx-menu_item-layout_id',
            'menu_item'
        );

        // drops foreign key for table `menu_item`
        $this->dropForeignKey(
            'fk-menu_item-parent_id',
            'menu_item'
        );

        // drops index for column `parent_id`
        $this->dropIndex(
            'idx-menu_item-parent_id',
            'menu_item'
        );

        $this->dropTable('menu_item');
    }
}
