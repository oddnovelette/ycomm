<?php
/**
 * Created by PhpStorm.
 * User: odd
 * Date: 20.08.2017
 * Time: 17:51
 */

namespace application\models\Items;

use yii\db\ActiveRecord;
/**
 * @property integer $item_id;
 * @property integer $related_id;
 *
 * Class RelatedAttachment
 * @package application\forms\Items
 */
class RelatedAttachment extends ActiveRecord
{
    public static function create(int $itemId) : self
    {
        $attachment = new static();
        $attachment->related_id = $itemId;
        return $attachment;
    }

    public function isForItem(int $id) : bool
    {
        return $this->related_id == $id;
    }

    public static function tableName() : string
    {
        return '{{%item_related_attachments}}';
    }
}