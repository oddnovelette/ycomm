<?php

use yii\db\Migration;

/**
 * Handles the creation of table `tag_attachments`.
 */
class m170825_233621_create_tag_attachments_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        $this->createTable('{{%item_tag_attachments}}', [
            'item_id' => $this->integer()->notNull(),
            'tag_id' => $this->integer()->notNull(),
        ], $tableOptions);
        $this->addPrimaryKey('{{%pk-item_tag_attachments}}', '{{%item_tag_attachments}}', ['item_id', 'tag_id']);
        $this->createIndex('{{%idx-item_tag_attachments-item_id}}', '{{%item_tag_attachments}}', 'item_id');
        $this->createIndex('{{%idx-item_tag_attachments-tag_id}}', '{{%item_tag_attachments}}', 'tag_id');
        $this->addForeignKey('{{%fk-item_tag_attachments-item_id}}', '{{%item_tag_attachments}}', 'item_id', '{{%app_items}}', 'id', 'CASCADE', 'RESTRICT');
        $this->addForeignKey('{{%fk-item_tag_attachments-tag_id}}', '{{%item_tag_attachments}}', 'tag_id', '{{%item_tags}}', 'id', 'CASCADE', 'RESTRICT');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('item_tag_attachments');
    }
}
