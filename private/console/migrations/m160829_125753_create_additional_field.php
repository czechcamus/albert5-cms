<?php

use yii\db\Migration;

/**
 * Handles the creation for table `additional_field`.
 * Has foreign keys to the tables:
 *
 * - `language`
 */
class m160829_125753_create_additional_field extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('additional_field', [
            'id' => $this->primaryKey(),
            'language_id' => $this->integer()->notNull(),
            'label' => $this->string()->notNull(),
        ]);

        // creates index for column `language_id`
        $this->createIndex(
            'idx-additional_field-language_id',
            'additional_field',
            'language_id'
        );

        // add foreign key for table `language`
        $this->addForeignKey(
            'fk-additional_field-language_id',
            'additional_field',
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
        // drops foreign key for table `language`
        $this->dropForeignKey(
            'fk-additional_field-language_id',
            'additional_field'
        );

        // drops index for column `language_id`
        $this->dropIndex(
            'idx-additional_field-language_id',
            'additional_field'
        );

        $this->dropTable('additional_field');
    }
}
