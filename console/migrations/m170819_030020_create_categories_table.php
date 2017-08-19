<?php

use yii\db\Migration;

/**
 * Handles the creation of table `categories`.
 */
class m170819_030020_create_categories_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';

        $this->createTable('{{%item_categories}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'slug' => $this->string()->notNull(),
            'title' => $this->string(),
            'description' => $this->text(),
            'meta_json' => 'JSON NOT NULL',
            'lft' => $this->integer()->notNull(),
            'rgt' => $this->integer()->notNull(),
            'depth' => $this->integer()->notNull(),
        ], $tableOptions);
        
        $this->createIndex('{{%idx-item_categories-slug}}', '{{%item_categories}}', 'slug', true);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('item_categories');
    }
}
