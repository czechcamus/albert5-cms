<?php

use yii\db\Migration;

/**
 * Handles the creation for table `content_tag`.
 * Has foreign keys to the tables:
 *
 * - `content`
 * - `tag`
 */
class m160829_131409_create_junction_content_and_tag extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('content_tag', [
            'content_id' => $this->integer(),
            'tag_id' => $this->integer(),
            'PRIMARY KEY(content_id, tag_id)',
        ]);

        // creates index for column `content_id`
        $this->createIndex(
            'idx-content_tag-content_id',
            'content_tag',
            'content_id'
        );

        // add foreign key for table `content`
        $this->addForeignKey(
            'fk-content_tag-content_id',
            'content_tag',
            'content_id',
            'content',
            'id',
            'CASCADE'
        );

        // creates index for column `tag_id`
        $this->createIndex(
            'idx-content_tag-tag_id',
            'content_tag',
            'tag_id'
        );

        // add foreign key for table `tag`
        $this->addForeignKey(
            'fk-content_tag-tag_id',
            'content_tag',
            'tag_id',
            'tag',
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
            'fk-content_tag-content_id',
            'content_tag'
        );

        // drops index for column `content_id`
        $this->dropIndex(
            'idx-content_tag-content_id',
            'content_tag'
        );

        // drops foreign key for table `tag`
        $this->dropForeignKey(
            'fk-content_tag-tag_id',
            'content_tag'
        );

        // drops index for column `tag_id`
        $this->dropIndex(
            'idx-content_tag-tag_id',
            'content_tag'
        );

        $this->dropTable('content_tag');
    }
}
