<?php

use yii\db\Migration;

/**
 * Handles the creation for table `poll`.
 * Has foreign keys to the tables:
 *
 * - `language`
 * - `user`
 * - `user`
 */
class m160829_160947_create_poll extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('poll', [
            'id' => $this->primaryKey(),
            'question' => $this->string()->notNull(),
            'end_date' => $this->date(),
            'language_id' => $this->integer()->notNull(),
            'active' => $this->boolean()->defaultValue(1),
            'main' => $this->boolean(),
            'created_at' => $this->dateTime(),
            'created_by' => $this->integer(),
            'updated_at' => $this->dateTime(),
            'updated_by' => $this->integer(),
        ]);

        // creates index for column `language_id`
        $this->createIndex(
            'idx-poll-language_id',
            'poll',
            'language_id'
        );

        // add foreign key for table `language`
        $this->addForeignKey(
            'fk-poll-language_id',
            'poll',
            'language_id',
            'language',
            'id',
            'CASCADE'
        );

        // creates index for column `created_by`
        $this->createIndex(
            'idx-poll-created_by',
            'poll',
            'created_by'
        );

        // add foreign key for table `user`
        $this->addForeignKey(
            'fk-poll-created_by',
            'poll',
            'created_by',
            'user',
            'id',
            'SET NULL'
        );

        // creates index for column `updated_by`
        $this->createIndex(
            'idx-poll-updated_by',
            'poll',
            'updated_by'
        );

        // add foreign key for table `user`
        $this->addForeignKey(
            'fk-poll-updated_by',
            'poll',
            'updated_by',
            'user',
            'id',
            'SET NULL'
        );
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        // drops foreign key for table `language`
        $this->dropForeignKey(
            'fk-poll-language_id',
            'poll'
        );

        // drops index for column `language_id`
        $this->dropIndex(
            'idx-poll-language_id',
            'poll'
        );

        // drops foreign key for table `user`
        $this->dropForeignKey(
            'fk-poll-created_by',
            'poll'
        );

        // drops index for column `created_by`
        $this->dropIndex(
            'idx-poll-created_by',
            'poll'
        );

        // drops foreign key for table `user`
        $this->dropForeignKey(
            'fk-poll-updated_by',
            'poll'
        );

        // drops index for column `updated_by`
        $this->dropIndex(
            'idx-poll-updated_by',
            'poll'
        );

        $this->dropTable('poll');
    }
}
