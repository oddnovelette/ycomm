<?php
/**
 * Created by PhpStorm.
 * User: odd
 * Date: 19.08.2017
 * Time: 17:08
 */

namespace application\models\Items;

use paulzi\nestedsets\NestedSetsBehavior;
use application\models\Items\Meta;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\helpers\Json;

/**
 * @property integer $id
 * @property string $name
 * @property string $slug
 * @property string $title
 * @property string $description
 * @property integer $lft
 * @property integer $rgt
 * @property integer $depth
 * @property Meta $meta
 *
 * @property Category $parent
 * @mixin NestedSetsBehavior
 */
class Category extends ActiveRecord
{
    public $meta;

    /**
     * @return string
     */
    public static function tableName() : string
    {
        return '{{%item_categories}}';
    }

    /**
     * @param string $name
     * @param string $slug
     * @param string $title
     * @param string $description
     * @param Meta $meta
     * @return Category
     */
    public static function create
    (
        string $name,
        string $slug,
        string $title,
        string $description,
        Meta $meta
    ) : self
    {
        $category = new self();
        $category->name = $name;
        $category->slug = $slug;
        $category->title = $title;
        $category->description = $description;
        $category->meta = $meta;
        return $category;
    }

    public function edit
    (
        string $name,
        string $slug,
        string $title,
        string $description,
        Meta $meta
    ) : void
    {
        $this->name = $name;
        $this->slug = $slug;
        $this->title = $title;
        $this->description = $description;
        $this->meta = $meta;
    }


    public function behaviors() : array
    {
        return [
            NestedSetsBehavior::className(),
        ];
    }

    /**
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert) : bool
    {
        $this->setAttribute('meta_json', Json::encode([
            'title' => $this->meta->title,
            'description' => $this->meta->description,
            'keywords' => $this->meta->keywords
        ]));
        return parent::beforeSave($insert);
    }

    public function afterFind() : void
    {
        $meta = Json::decode($this->getAttribute('meta_json'));
        $this->meta = new Meta(
            $meta['title'] ?? null,
            $meta['description'] ?? null,
            $meta['keywords'] ?? null
        );
        parent::afterFind();
    }

    public function transactions() : array
    {
        return [
            self::SCENARIO_DEFAULT => self::OP_ALL,
        ];
    }

    public static function find() : ActiveQuery
    {
        return new ActiveQuery(static::class);
    }
}