<?php

use yii\db\Migration;

/**
 * Handles the creation for table `page_field`.
 * Has foreign keys to the tables:
 *
 * - `content`
 * - `additional_field`
 */
class m160829_130113_create_page_field extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('page_field', [
            'id' => $this->primaryKey(),
            'page_id' => $this->integer(),
            'additional_field_id' => $this->integer(),
            'content' => $this->string(),
        ]);

        // creates index for column `page_id`
        $this->createIndex(
            'idx-page_field-page_id',
            'page_field',
            'page_id'
        );

        // add foreign key for table `content`
        $this->addForeignKey(
            'fk-page_field-page_id',
            'page_field',
            'page_id',
            'content',
            'id',
            'CASCADE'
        );

        // creates index for column `additional_field_id`
        $this->createIndex(
            'idx-page_field-additional_field_id',
            'page_field',
            'additional_field_id'
        );

        // add foreign key for table `additional_field`
        $this->addForeignKey(
            'fk-page_field-additional_field_id',
            'page_field',
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
            'fk-page_field-page_id',
            'page_field'
        );

        // drops index for column `page_id`
        $this->dropIndex(
            'idx-page_field-page_id',
            'page_field'
        );

        // drops foreign key for table `additional_field`
        $this->dropForeignKey(
            'fk-page_field-additional_field_id',
            'page_field'
        );

        // drops index for column `additional_field_id`
        $this->dropIndex(
            'idx-page_field-additional_field_id',
            'page_field'
        );

        $this->dropTable('page_field');
    }
}
