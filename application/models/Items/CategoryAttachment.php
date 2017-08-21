<?php
/**
 * Created by PhpStorm.
 * User: odd
 * Date: 20.08.2017
 * Time: 17:46
 */

namespace application\models\Items;

use yii\db\ActiveRecord;

/**
 * @property integer $item_id;
 * @property integer $related_id;
 *
 * Class CategoryAttachment
 * @package application\forms\Items
 */
class CategoryAttachment extends ActiveRecord
{
    /**
     * @param int $itemId
     * @return CategoryAttachment
     */
    public static function create(int $itemId) : self
    {
        $attachment = new static();
        $attachment->category_id = $itemId;
        return $attachment;
    }

    /**
     * @param int $id
     * @return bool
     */
    public function isForItem(int $id) : bool
    {
        return $this->related_id == $id;
    }

    public function isForCategory($id) : bool
    {
        return $this->category_id == $id;
    }

    /**
     * @return string
     */
    public static function tableName() : string
    {
        return '{{%item_related_attachments}}';
    }
}