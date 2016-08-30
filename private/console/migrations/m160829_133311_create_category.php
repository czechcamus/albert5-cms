<?php

use yii\db\Migration;

/**
 * Handles the creation for table `category`.
 * Has foreign keys to the tables:
 *
 * - `language`
 * - `user`
 * - `user`
 */
class m160829_133311_create_category extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('category', [
            'id' => $this->primaryKey(),
            'title' => $this->string()->notNull(),
            'description' => $this->text(),
            'category_type' => $this->smallInteger(6),
            'language_id' => $this->integer()->notNull(),
            'active' => $this->boolean()->defaultValue(1),
            'main' => $this->boolean(),
            'public' => $this->boolean()->defaultValue(1),
            'created_at' => $this->dateTime(),
            'created_by' => $this->integer(),
            'updated_at' => $this->dateTime(),
            'updated_by' => $this->integer(),
        ]);

        // creates index for column `language_id`
        $this->createIndex(
            'idx-category-language_id',
            'category',
            'language_id'
        );

        // add foreign key for table `language`
        $this->addForeignKey(
            'fk-category-language_id',
            'category',
            'language_id',
            'language',
            'id',
            'CASCADE'
        );

        // creates index for column `created_by`
        $this->createIndex(
            'idx-category-created_by',
            'category',
            'created_by'
        );

        // add foreign key for table `user`
        $this->addForeignKey(
            'fk-category-created_by',
            'category',
            'created_by',
            'user',
            'id',
            'CASCADE'
        );

        // creates index for column `updated_by`
        $this->createIndex(
            'idx-category-updated_by',
            'category',
            'updated_by'
        );

        // add foreign key for table `user`
        $this->addForeignKey(
            'fk-category-updated_by',
            'category',
            'updated_by',
            'user',
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
            'fk-category-language_id',
            'category'
        );

        // drops index for column `language_id`
        $this->dropIndex(
            'idx-category-language_id',
            'category'
        );

        // drops foreign key for table `user`
        $this->dropForeignKey(
            'fk-category-created_by',
            'category'
        );

        // drops index for column `created_by`
        $this->dropIndex(
            'idx-category-created_by',
            'category'
        );

        // drops foreign key for table `user`
        $this->dropForeignKey(
            'fk-category-updated_by',
            'category'
        );

        // drops index for column `updated_by`
        $this->dropIndex(
            'idx-category-updated_by',
            'category'
        );

        $this->dropTable('category');
    }
}
