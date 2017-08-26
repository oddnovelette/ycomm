<?php

use yii\db\Migration;

/**
 * Handles the creation of table `item_overviews`.
 */
class m170826_000917_create_item_overviews_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        $this->createTable('{{%item_overviews}}', [
            'id' => $this->primaryKey(),
            'created_at' => $this->integer()->unsigned()->notNull(),
            'user_id' => $this->integer()->notNull(),
            'vote' => $this->integer()->notNull(),
            'text' => $this->text()->notNull(),
            'active' => $this->boolean()->notNull(),
        ], $tableOptions);

        $this->createIndex('{{%idx-item_overviews-user_id}}', '{{%item_overviews}}', 'user_id');
        $this->addForeignKey('{{%fk-item_overviews-user_id}}', '{{%item_overviews}}', 'user_id', '{{%users}}', 'id', 'CASCADE', 'RESTRICT');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('item_overviews');
    }
}
