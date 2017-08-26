<?php

use yii\db\Migration;

/**
 * Handles the creation of table `item_related_attachments`.
 */
class m170825_234033_create_item_related_attachments_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        $this->createTable('{{%item_related_attachments}}', [
            'item_id' => $this->integer()->notNull(),
            'related_id' => $this->integer()->notNull(),
        ], $tableOptions);

        $this->addPrimaryKey('{{%pk-item_related_attachments}}', '{{%item_related_attachments}}', ['item_id', 'related_id']);
        $this->createIndex('{{%idx-item_related_attachments-item_id}}', '{{%item_related_attachments}}', 'item_id');
        $this->createIndex('{{%idx-item_related_attachments-related_id}}', '{{%item_related_attachments}}', 'related_id');
        $this->addForeignKey('{{%fk-item_related_attachments-item_id}}', '{{%item_related_attachments}}', 'item_id', '{{%app_items}}', 'id', 'CASCADE', 'RESTRICT');
        $this->addForeignKey('{{%fk-item_related_attachments-related_id}}', '{{%item_related_attachments}}', 'related_id', '{{%app_items}}', 'id', 'CASCADE', 'RESTRICT');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('item_related_attachments');
    }
}
