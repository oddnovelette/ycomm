<?php

use yii\db\Migration;

/**
 * Handles the creation of table `tags`.
 */
class m170818_145939_create_tags_table extends Migration
{
    public function up()
    {
        $this->createTable('{{%item_tags}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'slug' => $this->string()->notNull(),
        ]);
    }

    public function down()
    {
        $this->dropTable('{{%item_tags}}');
    }
}
