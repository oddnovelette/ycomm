<?php

use yii\db\Migration;

/**
 * Handles the creation of table `items`.
 */
class m170825_222824_create_items_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        $this->createTable('{{%app_items}}', [
            'id' => $this->primaryKey(),
            'category_id' => $this->integer()->notNull(),
            'label_id' => $this->integer()->notNull(),
            'created_at' => $this->integer()->unsigned()->notNull(),
            'code' => $this->string()->notNull(),
            'name' => $this->string()->notNull(),
            'text' => $this->text(),
            'price_old' => $this->integer(),
            'price_new' => $this->integer(),
            'rating' => $this->decimal(3, 2),
            'meta_json' => $this->text(),
        ], $tableOptions);

        $this->createIndex('{{%idx-app_items-code}}', '{{%app_items}}', 'code', true);
        $this->createIndex('{{%idx-app_items-category_id}}', '{{%app_items}}', 'category_id');
        $this->createIndex('{{%idx-app_items-label_id}}', '{{%app_items}}', 'label_id');

        $this->addForeignKey('{{%fk-app_items-category_id}}', '{{%app_items}}', 'category_id', '{{%item_categories}}', 'id');
        $this->addForeignKey('{{%fk-app_items-label_id}}', '{{%app_items}}', 'label_id', '{{%item_labels}}', 'id');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('app_items');
    }
}
