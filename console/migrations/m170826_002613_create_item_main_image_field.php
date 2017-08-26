<?php

use yii\db\Migration;

class m170826_002613_create_item_main_image_field extends Migration
{
    public function up()
    {
        $this->addColumn('{{%app_items}}', 'main_image_id', $this->integer());
        $this->createIndex('{{%idx-app_items-main_image_id}}', '{{%app_items}}', 'main_image_id');
        $this->addForeignKey('{{%fk-app_items-main_image_id}}', '{{%app_items}}', 'main_image_id', '{{%item_images}}', 'id', 'SET NULL', 'RESTRICT');
    }
    public function down()
    {
        $this->dropForeignKey('{{%fk-app_items-main_image_id}}', '{{%app_items}}');
        $this->dropColumn('{{%app_items}}', 'main_image_id');
    }
}
