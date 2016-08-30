<?php

use yii\db\Migration;

/**
 * Handles the creation for table `image_gallery`.
 * Has foreign keys to the tables:
 *
 * - `file`
 * - `category`
 */
class m160829_160315_create_image_gallery extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('image_gallery', [
            'id' => $this->primaryKey(),
            'image_id' => $this->integer(),
            'gallery_id' => $this->integer(),
            'item_order' => $this->smallInteger(6),
        ]);

        // creates index for column `image_id`
        $this->createIndex(
            'idx-image_gallery-image_id',
            'image_gallery',
            'image_id'
        );

        // add foreign key for table `file`
        $this->addForeignKey(
            'fk-image_gallery-image_id',
            'image_gallery',
            'image_id',
            'file',
            'id',
            'CASCADE'
        );

        // creates index for column `gallery_id`
        $this->createIndex(
            'idx-image_gallery-gallery_id',
            'image_gallery',
            'gallery_id'
        );

        // add foreign key for table `category`
        $this->addForeignKey(
            'fk-image_gallery-gallery_id',
            'image_gallery',
            'gallery_id',
            'category',
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
            'fk-image_gallery-image_id',
            'image_gallery'
        );

        // drops index for column `image_id`
        $this->dropIndex(
            'idx-image_gallery-image_id',
            'image_gallery'
        );

        // drops foreign key for table `category`
        $this->dropForeignKey(
            'fk-image_gallery-gallery_id',
            'image_gallery'
        );

        // drops index for column `gallery_id`
        $this->dropIndex(
            'idx-image_gallery-gallery_id',
            'image_gallery'
        );

        $this->dropTable('image_gallery');
    }
}
