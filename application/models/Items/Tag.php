<?php

namespace application\models\Items;

/**
 * Created by PhpStorm.
 * User: odd
 * Date: 18.08.2017
 * Time: 17:46
 */
/**
 * Class Tag
 *
 * @property integer $id
 * @property string $name
 * @property string $slug
 * @package application\models\Items
 */
class Tag extends \yii\db\ActiveRecord
{
    public static function tableName() : string
    {
        return '{{%item_tags}}';
    }

    /**
     * @param string $name
     * @param string $slug
     * @return Tag
     */
    public static function create(string $name, string $slug) : self
    {
        $tag = new self();
        $tag->name = $name;
        $tag->slug = $slug;
        return $tag;
    }

    /**
     * @param string $name
     * @param string $slug
     */
    public function edit(string $name, string $slug) : void
    {
        $this->name = $name;
        $this->slug = $slug;
    }
}