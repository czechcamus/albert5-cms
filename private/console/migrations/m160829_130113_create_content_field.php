<?php

use yii\db\Migration;

/**
 * Handles the creation for table `content_field`.
 * Has foreign keys to the tables:
 *
 * - `content`
 * - `additional_field`
 */
class m160829_130113_create_content_field extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('content_field', [
            'id' => $this->primaryKey(),
            'content_id' => $this->integer(),
            'additional_field_id' => $this->integer(),
            'content' => $this->string(),
        ]);

        // creates index for column `content_id`
        $this->createIndex(
            'idx-content_field-content_id',
            'content_field',
            'content_id'
        );

        // add foreign key for table `content`
        $this->addForeignKey(
            'fk-content_field-content_id',
            'content_field',
            'content_id',
            'content',
            'id',
            'CASCADE'
        );

        // creates index for column `additional_field_id`
        $this->createIndex(
            'idx-content_field-additional_field_id',
            'content_field',
            'additional_field_id'
        );

        // add foreign key for table `additional_field`
        $this->addForeignKey(
            'fk-content_field-additional_field_id',
            'content_field',
            'additional_field_id',
            'additional_field',
            'id',
            'CASCADE'
        );
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        // drops foreign key for table `content`
        $this->dropForeignKey(
            'fk-content_field-content_id',
            'content_field'
        );

        // drops index for column `content_id`
        $this->dropIndex(
            'idx-content_field-content_id',
            'content_field'
        );

        // drops foreign key for table `additional_field`
        $this->dropForeignKey(
            'fk-content_field-additional_field_id',
            'content_field'
        );

        // drops index for column `additional_field_id`
        $this->dropIndex(
            'idx-content_field-additional_field_id',
            'content_field'
        );

        $this->dropTable('content_field');
    }
}
