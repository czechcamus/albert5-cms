<?php

use yii\db\Migration;

/**
 * Handles the creation for table `tag`.
 * Has foreign keys to the tables:
 *
 * - `language`
 */
class m160829_130810_create_tag extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('tag', [
            'id' => $this->primaryKey(),
            'frequency' => $this->integer(),
            'name' => $this->string()->notNull(),
            'language_id' => $this->integer(),
        ]);

        // creates index for column `language_id`
        $this->createIndex(
            'idx-tag-language_id',
            'tag',
            'language_id'
        );

        // add foreign key for table `language`
        $this->addForeignKey(
            'fk-tag-language_id',
            'tag',
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
            'fk-tag-language_id',
            'tag'
        );

        // drops index for column `language_id`
        $this->dropIndex(
            'idx-tag-language_id',
            'tag'
        );

        $this->dropTable('tag');
    }
}
