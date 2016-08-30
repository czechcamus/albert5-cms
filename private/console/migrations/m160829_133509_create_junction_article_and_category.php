<?php

use yii\db\Migration;

/**
 * Handles the creation for table `article_category`.
 * Has foreign keys to the tables:
 *
 * - `article`
 * - `category`
 */
class m160829_133509_create_junction_article_and_category extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('article_category', [
            'article_id' => $this->integer(),
            'category_id' => $this->integer(),
            'PRIMARY KEY(article_id, category_id)',
        ]);


        // creates index for column `article_id`
        $this->createIndex(
            'idx-article_category-article_id',
            'article_category',
            'article_id'
        );

        // add foreign key for table `article`
        $this->addForeignKey(
            'fk-article_category-article_id',
            'article_category',
            'article_id',
            'content',
            'id',
            'CASCADE'
        );

        // creates index for column `category_id`
        $this->createIndex(
            'idx-article_category-category_id',
            'article_category',
            'category_id'
        );

        // add foreign key for table `category`
        $this->addForeignKey(
            'fk-article_category-category_id',
            'article_category',
            'category_id',
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
        // drops foreign key for table `article`
        $this->dropForeignKey(
            'fk-article_category-article_id',
            'article_category'
        );

        // drops index for column `article_id`
        $this->dropIndex(
            'idx-article_category-article_id',
            'article_category'
        );

        // drops foreign key for table `category`
        $this->dropForeignKey(
            'fk-article_category-category_id',
            'article_category'
        );

        // drops index for column `category_id`
        $this->dropIndex(
            'idx-article_category-category_id',
            'article_category'
        );

        $this->dropTable('article_category');
    }
}
