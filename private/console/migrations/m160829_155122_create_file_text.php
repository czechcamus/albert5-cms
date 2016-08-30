<?php

use yii\db\Migration;

/**
 * Handles the creation for table `file_text`.
 * Has foreign keys to the tables:
 *
 * - `file`
 * - `language`
 */
class m160829_155122_create_file_text extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('file_text', [
            'id' => $this->primaryKey(),
            'title' => $this->string(),
            'description' => $this->text(),
            'file_id' => $this->integer(),
            'language_id' => $this->integer(),
        ]);

        // creates index for column `file_id`
        $this->createIndex(
            'idx-file_text-file_id',
            'file_text',
            'file_id'
        );

        // add foreign key for table `file`
        $this->addForeignKey(
            'fk-file_text-file_id',
            'file_text',
            'file_id',
            'file',
            'id',
            'CASCADE'
        );

        // creates index for column `language_id`
        $this->createIndex(
            'idx-file_text-language_id',
            'file_text',
            'language_id'
        );

        // add foreign key for table `language`
        $this->addForeignKey(
            'fk-file_text-language_id',
            'file_text',
            'language_id',
            'language',
            'id',
            'CASCADE'
        );
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        // drops foreign key for table `file`
        $this->dropForeignKey(
            'fk-file_text-file_id',
            'file_text'
        );

        // drops index for column `file_id`
        $this->dropIndex(
            'idx-file_text-file_id',
            'file_text'
        );

        // drops foreign key for table `language`
        $this->dropForeignKey(
            'fk-file_text-language_id',
            'file_text'
        );

        // drops index for column `language_id`
        $this->dropIndex(
            'idx-file_text-language_id',
            'file_text'
        );

        $this->dropTable('file_text');
    }
}
