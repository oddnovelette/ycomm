<?php
/**
 * Created by PhpStorm.
 * User: odd
 * Date: 20.08.2017
 * Time: 16:32
 */

namespace application\models\Items;

use yii\db\ActiveRecord;
use yii\web\UploadedFile;
use yiidreamteam\upload\ImageUploadBehavior;

/**
 * @property integer $id
 * @property string $file
 * @property integer $sort
 * @mixin ImageUploadBehavior
 *
 * Class Image
 * @package application\models\Items
 */
class Image extends ActiveRecord
{
    /**
     * @param UploadedFile $file
     * @return Image
     */
    public static function create(UploadedFile $file) : self
    {
        $photo = new self();
        $photo->file = $file;
        return $photo;
    }

    public function setSort($sort) : void
    {
        $this->sort = $sort;
    }

    public function isIdEqualTo($id) : bool
    {
        return $this->id == $id;
    }

    public static function tableName() : string
    {
        return '{{%app_images}}';
    }

    public function behaviors() : array
    {
        return [
            [
                'class' => ImageUploadBehavior::className(),
                'attribute' => 'file',
                'createThumbsOnRequest' => true,
                'filePath' => '@uploadRoot/original/items/[[attribute_item_id]]/[[id]].[[extension]]',
                'fileUrl' => '@upload/original/items/[[attribute_item_id]]/[[id]].[[extension]]',
                'thumbPath' => '@uploadRoot/cache/items/[[attribute_item_id]]/[[profile]]_[[id]].[[extension]]',
                'thumbUrl' => '@upload/cache/items/[[attribute_item_id]]/[[profile]]_[[id]].[[extension]]',
                'thumbs' => [
                    'admin' => ['width' => 100, 'height' => 70],
                    'thumb' => ['width' => 640, 'height' => 480],
                ],
            ],
        ];
    }
}