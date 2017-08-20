<?php
/**
 * Created by PhpStorm.
 * User: odd
 * Date: 20.08.2017
 * Time: 16:35
 */

namespace application\models\Items;


use yii\db\ActiveRecord;

class ParameterValue extends ActiveRecord
{
    public static function create($parameterId, $value): self
    {
        $object = new static();
        $object->parameter_id = $parameterId;
        $object->value = $value;
        return $object;
    }
    public static function blank($parameterId): self
    {
        $object = new static();
        $object->parameter_id = $parameterId;
        return $object;
    }
    public function change($value): void
    {
        $this->value = $value;
    }
    public function isForParameter($id): bool
    {
        return $this->parameter_id == $id;
    }
    public static function tableName(): string
    {
        return '{{%item_parameter_values}}';
    }
}