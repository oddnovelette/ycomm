<?php

use yii\db\Migration;

/**
 * Handles the creation of table `item_category_attachments`.
 */
class m170825_224247_create_item_category_attachments_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        $this->createTable('{{%item_category_attachments}}', [
            'item_id' => $this->integer()->notNull(),
            'category_id' => $this->integer()->notNull(),
        ], $tableOptions);

        $this->addPrimaryKey('{{%pk-item_category_attachments}}', '{{%item_category_attachments}}', ['item_id', 'category_id']);
        $this->createIndex('{{%idx-item_category_attachments-item_id}}', '{{%item_category_attachments}}', 'item_id');
        $this->createIndex('{{%idx-item_category_attachments-category_id}}', '{{%item_category_attachments}}', 'category_id');
        $this->addForeignKey('{{%fk-item_category_attachments-item_id}}', '{{%item_category_attachments}}', 'item_id', '{{%app_items}}', 'id', 'CASCADE', 'RESTRICT');
        $this->addForeignKey('{{%fk-item_category_attachments-category_id}}', '{{%item_category_attachments}}', 'category_id', '{{%item_categories}}', 'id', 'CASCADE', 'RESTRICT');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('item_category_attachments');
    }
}
