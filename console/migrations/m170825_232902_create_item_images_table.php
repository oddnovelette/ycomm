<?php

use yii\db\Migration;

/**
 * Handles the creation of table `item_images`.
 */
class m170825_232902_create_item_images_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        $this->createTable('{{%item_images}}', [
            'id' => $this->primaryKey(),
            'item_id' => $this->integer()->notNull(),
            'file' => $this->string()->notNull(),
            'sort' => $this->integer()->notNull(),
        ], $tableOptions);
        $this->createIndex('{{%idx-item_images-item_id}}', '{{%item_images}}', 'item_id');
        $this->addForeignKey('{{%fk-item_images-item_id}}', '{{%item_images}}', 'item_id', '{{%app_items}}', 'id', 'CASCADE', 'RESTRICT');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('item_images');
    }
}
