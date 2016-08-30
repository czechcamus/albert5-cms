<?php

use yii\db\Migration;

/**
 * Handles the creation for table `email`.
 * Has foreign keys to the tables:
 *
 * - `language`
 */
class m160829_142417_create_email extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('email', [
            'id' => $this->primaryKey(),
            'email' => $this->string()->notNull()->unique(),
            'hash' => $this->string(),
            'active' => $this->boolean()->defaultValue(1),
            'created_at' => $this->dateTime(),
            'language_id' => $this->integer()->notNull(),
        ]);

        // creates index for column `language_id`
        $this->createIndex(
            'idx-email-language_id',
            'email',
            'language_id'
        );

        // add foreign key for table `language`
        $this->addForeignKey(
            'fk-email-language_id',
            'email',
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
            'fk-email-language_id',
            'email'
        );

        // drops index for column `language_id`
        $this->dropIndex(
            'idx-email-language_id',
            'email'
        );

        $this->dropTable('email');
    }
}
