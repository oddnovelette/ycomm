<?php
/**
 * Created by PhpStorm.
 * User: odd
 * Date: 20.08.2017
 * Time: 18:04
 */

namespace application\models\Items;

use yii\db\ActiveRecord;

/**
 * @property integer $item_id;
 * @property integer $tag_id;
 *
 * Class TagAttachment
 * @package application\forms\Items
 */
class TagAttachment extends ActiveRecord
{
    /**
     * @param int $tagId
     * @return TagAttachment
     */
    public static function create(int $tagId) : self
    {
        $attachment = new static();
        $attachment->tag_id = $tagId;
        return $attachment;
    }

    /**
     * @param int $id
     * @return bool
     */
    public function isForTag(int $id) : bool
    {
        return $this->tag_id == $id;
    }

    /**
     * @return string
     */
    public static function tableName() : string
    {
        return '{{%item_tag_attachments}}';
    }
}