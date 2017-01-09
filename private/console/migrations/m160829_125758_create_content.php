<?php

use yii\db\Migration;

/**
 * Handles the creation for table `content`.
 * Has foreign keys to the tables:
 *
 * - `file`
 * - `language`
 * - `user`
 * - `user`
 * - `layout`
 */
class m160829_125758_create_content extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('content', [
            'id' => $this->primaryKey(),
            'title' => $this->string()->notNull(),
            'perex' => $this->text(),
            'description' => $this->text(),
            'content_date' => $this->date(),
            'content_time' => $this->time(),
            'content_end_date' => $this->date(),
            'image_id' => $this->integer(),
            'language_id' => $this->integer()->notNull(),
            'active' => $this->boolean()->defaultValue(1),
            'public' => $this->boolean()->defaultValue(1),
            'created_at' => $this->dateTime(),
            'created_by' => $this->integer(),
            'updated_at' => $this->dateTime(),
            'updated_by' => $this->integer(),
            'content_type' => $this->smallInteger(6),
            'layout_id' => $this->integer(),
	        'order_time' => $this->dateTime()
        ]);

        // creates index for column `image_id`
        $this->createIndex(
            'idx-content-image_id',
            'content',
            'image_id'
        );

        // add foreign key for table `file`
        $this->addForeignKey(
            'fk-content-image_id',
            'content',
            'image_id',
            'file',
            'id',
            'SET NULL'
        );

        // creates index for column `language_id`
        $this->createIndex(
            'idx-content-language_id',
            'content',
            'language_id'
        );

        // add foreign key for table `language`
        $this->addForeignKey(
            'fk-content-language_id',
            'content',
            'language_id',
            'language',
            'id',
            'CASCADE'
        );

        // creates index for column `created_by`
        $this->createIndex(
            'idx-content-created_by',
            'content',
            'created_by'
        );

        // add foreign key for table `user`
        $this->addForeignKey(
            'fk-content-created_by',
            'content',
            'created_by',
            'user',
            'id',
            'SET NULL'
        );

        // creates index for column `updated_by`
        $this->createIndex(
            'idx-content-updated_by',
            'content',
            'updated_by'
        );

        // add foreign key for table `user`
        $this->addForeignKey(
            'fk-content-updated_by',
            'content',
            'updated_by',
            'user',
            'id',
            'SET NULL'
        );

        // creates index for column `layout_id`
        $this->createIndex(
            'idx-content-layout_id',
            'content',
            'layout_id'
        );

        // add foreign key for table `layout`
        $this->addForeignKey(
            'fk-content-layout_id',
            'content',
            'layout_id',
            'layout',
            'id',
            'SET NULL'
        );
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        // drops foreign key for table `file`
        $this->dropForeignKey(
            'fk-content-image_id',
            'content'
        );

        // drops index for column `image_id`
        $this->dropIndex(
            'idx-content-image_id',
            'content'
        );

        // drops foreign key for table `language`
        $this->dropForeignKey(
            'fk-content-language_id',
            'content'
        );

        // drops index for column `language_id`
        $this->dropIndex(
            'idx-content-language_id',
            'content'
        );

        // drops foreign key for table `user`
        $this->dropForeignKey(
            'fk-content-created_by',
            'content'
        );

        // drops index for column `created_by`
        $this->dropIndex(
            'idx-content-created_by',
            'content'
        );

        // drops foreign key for table `user`
        $this->dropForeignKey(
            'fk-content-updated_by',
            'content'
        );

        // drops index for column `updated_by`
        $this->dropIndex(
            'idx-content-updated_by',
            'content'
        );

        // drops foreign key for table `layout`
        $this->dropForeignKey(
            'fk-content-layout_id',
            'content'
        );

        // drops index for column `layout_id`
        $this->dropIndex(
            'idx-content-layout_id',
            'content'
        );

        $this->dropTable('content');
    }
}
