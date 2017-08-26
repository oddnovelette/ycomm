<?php

use yii\db\Migration;

/**
 * Handles the creation of table `item_variations`.
 */
class m170825_234603_create_item_variations_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';

        $this->createTable('{{%item_variations}}', [
            'id' => $this->primaryKey(),
            'item_id' => $this->integer()->notNull(),
            'code' => $this->string()->notNull(),
            'name' => $this->string()->notNull(),
            'price' => $this->integer(),
        ], $tableOptions);

        $this->createIndex('{{%idx-item_variations-code}}', '{{%item_variations}}', 'code');
        $this->createIndex('{{%idx-item_variations-item_id-code}}', '{{%item_variations}}', ['item_id', 'code'], true);
        $this->createIndex('{{%idx-item_variations-item_id}}', '{{%item_variations}}', 'item_id');
        $this->addForeignKey('{{%fk-item_variations-item_id}}', '{{%item_variations}}', 'item_id', '{{%app_items}}', 'id', 'CASCADE', 'RESTRICT');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('item_variations');
    }
}
