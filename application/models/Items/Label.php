<?php
/**
 * Created by PhpStorm.
 * User: odd
 * Date: 19.08.2017
 * Time: 00:12
 */

namespace application\models\Items;

use yii\db\ActiveRecord;
use yii\helpers\Json;

/**
 * @property integer $id
 * @property string $name
 * @property string $slug
 * @property Meta $meta
 *
 * Class Label
 * @package application\models\Items
 */
class Label extends ActiveRecord
{
    public $meta = null;

    /**
     * @return string
     */
    public static function tableName() : string
    {
        return '{{%item_labels}}';
    }

    /**
     * @param string $name
     * @param string $slug
     * @param Meta $meta
     * @return Label
     */
    public static function create(string $name, string $slug, Meta $meta) : self
    {
        $brand = new self();
        $brand->name = $name;
        $brand->slug = $slug;
        $brand->meta = $meta;
        return $brand;
    }

    /**
     * @param string $name
     * @param string $slug
     * @param Meta $meta
     */
    public function edit(string $name, string $slug, Meta $meta) : void
    {
        $this->name = $name;
        $this->slug = $slug;
        $this->meta = $meta;
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
}