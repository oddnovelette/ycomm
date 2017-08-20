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

class Image extends ActiveRecord
{
    public static function create(UploadedFile $file): self
    {
        $photo = new static();
        $photo->file = $file;
        return $photo;
    }
    public function setSort($sort): void
    {
        $this->sort = $sort;
    }
    public function isIdEqualTo($id): bool
    {
        return $this->id == $id;
    }
    public static function tableName(): string
    {
        return '{{%item_images}}';
    }
}