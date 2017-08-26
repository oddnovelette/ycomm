<?php

use yii\db\Migration;

/**
 * Handles the creation of table `item_values`.
 */
class m170825_225014_create_item_values_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        $this->createTable('{{%item_values}}', [
            'item_id' => $this->integer()->notNull(),
            'parameter_id' => $this->integer()->notNull(),
            'value' => $this->string(),
        ], $tableOptions);
        $this->addPrimaryKey('{{%pk-item_values}}', '{{%item_values}}', ['item_id', 'parameter_id']);
        $this->createIndex('{{%idx-item_values-item_id}}', '{{%item_values}}', 'item_id');
        $this->createIndex('{{%idx-item_values-parameter_id}}', '{{%item_values}}', 'parameter_id');
        $this->addForeignKey('{{%fk-item_values-item_id}}', '{{%item_values}}', 'item_id', '{{%app_items}}', 'id', 'CASCADE', 'RESTRICT');
        $this->addForeignKey('{{%fk-item_values-parameter_id}}', '{{%item_values}}', 'parameter_id', '{{%item_parameters}}', 'id', 'CASCADE', 'RESTRICT');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('item_values');
    }
}
