<?php

use yii\db\Migration;

class m170814_132648_user_socials extends Migration
{
    public function up()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';

        $this->createTable('{{%user_socials}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'identity' => $this->string()->notNull(),
            'social_network' => $this->string(16)->notNull(),
        ], $tableOptions);

        // creates unique composite index for (identity->social_network) with 'true' flag
        $this->createIndex('{{%idx-user_socials-identity-name}}', '{{%user_socials}}', ['identity', 'social_network'], true);

        $this->createIndex('{{%idx-user_socials-user_id}}', '{{%user_socials}}', 'user_id');

        $this->addForeignKey('{{%fk-user_socials-user_id}}', '{{%user_socials}}', 'user_id', '{{%users}}', 'id', 'CASCADE');
    }

    public function down()
    {
        $this->dropTable('{{%user_socials}}');
    }
}
