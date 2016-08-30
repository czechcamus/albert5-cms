<?php

use yii\db\Migration;

/**
 * Handles the creation for table `poll_answer`.
 * Has foreign keys to the tables:
 *
 * - `poll`
 */
class m160829_161257_create_poll_answer extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('poll_answer', [
            'id' => $this->primaryKey(),
            'answer' => $this->string()->notNull(),
            'voices' => $this->integer(),
            'poll_id' => $this->integer()->notNull(),
        ]);

        // creates index for column `poll_id`
        $this->createIndex(
            'idx-poll_answer-poll_id',
            'poll_answer',
            'poll_id'
        );

        // add foreign key for table `poll`
        $this->addForeignKey(
            'fk-poll_answer-poll_id',
            'poll_answer',
            'poll_id',
            'poll',
            'id',
            'CASCADE'
        );
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        // drops foreign key for table `poll`
        $this->dropForeignKey(
            'fk-poll_answer-poll_id',
            'poll_answer'
        );

        // drops index for column `poll_id`
        $this->dropIndex(
            'idx-poll_answer-poll_id',
            'poll_answer'
        );

        $this->dropTable('poll_answer');
    }
}
