<?php
/**
 * Created by PhpStorm.
 * User: odd
 * Date: 20.08.2017
 * Time: 16:30
 */

namespace application\models\Items;


use yii\db\ActiveRecord;
use yii\helpers\Json;

/**
 * @property integer $id
 * @property string $name
 * @property string $type
 * @property string $required
 * @property string $default
 * @property array $variants
 * @property integer $sort
 *
 * Class Parameter
 * @package application\models\Items
 */
class Parameter extends ActiveRecord
{
    const TYPE_STRING = 'string';
    const TYPE_INTEGER = 'integer';
    const TYPE_FLOAT = 'float';

    public $variants;

    public static function tableName() : string
    {
        return '{{%item_parameters}}';
    }

    public static function create(string $name, string $type, string $required, string $default, array $variants, int $sort) : self
    {
        $object = new self();
        $object->name = $name;
        $object->type = $type;
        $object->required = $required;
        $object->default = $default;
        $object->variants = $variants;
        $object->sort = $sort;
        return $object;
    }

    public function edit(string $name, string $type, string $required, string $default, array $variants, int $sort) : void
    {
        $this->name = $name;
        $this->type = $type;
        $this->required = $required;
        $this->default = $default;
        $this->variants = $variants;
        $this->sort = $sort;
    }

    public function isString() : bool
    {
        return $this->type === self::TYPE_STRING;
    }

    public function isInteger() : bool
    {
        return $this->type === self::TYPE_INTEGER;
    }

    public function isFloat() : bool
    {
        return $this->type === self::TYPE_FLOAT;
    }

    public function isSelect() : bool
    {
        return count($this->variants) > 0;
    }

    public function afterFind(): void
    {
        $this->variants = Json::decode($this->getAttribute('variants_json'));
        parent::afterFind();
    }
    public function beforeSave($insert): bool
    {
        $this->setAttribute('variants_json', Json::encode($this->variants));
        return parent::beforeSave($insert);
    }
}