<?php

use yii\db\Migration;

/**
 * Handles the creation of table `item_labels`.
 */
class m170819_025949_create_labels_table extends Migration
{
    public function up()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        $this->createTable('{{%item_labels}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'slug' => $this->string()->notNull(),
            'meta_json' => 'JSON NOT NULL', // Be aware with MariaDB
        ], $tableOptions);
        $this->createIndex('{{%idx-labels-slug}}', '{{%item_labels}}', 'slug', true);
    }
    
    public function down()
    {
        $this->dropTable('{{%item_labels}}');
    }
}
