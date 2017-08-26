<?php
/**
 * Created by PhpStorm.
 * User: odd
 * Date: 20.08.2017
 * Time: 16:35
 */

namespace application\models\Items;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * Class ParameterValue
 * @package application\models\Items
 */
class ParameterValue extends ActiveRecord
{
    public static function tableName() : string
    {
        return '{{%item_values}}';
    }

    /**
     * @param int $parameterId
     * @param string $value
     * @return ParameterValue
     */
    public static function create(int $parameterId, string $value) : self
    {
        $object = new static();
        $object->parameter_id = $parameterId;
        $object->value = $value;
        return $object;
    }

    /**
     * @param string $value
     * @return void
     */
    public function change(string $value) : void
    {
        $this->value = $value;
    }

    /**
     * @param int $id
     * @return bool
     */
    public function isForParameter(int $id) : bool
    {
        return $this->parameter_id == $id;
    }

    /**
     * @return ActiveQuery
     */
    public function getParameter() : ActiveQuery
    {
        return $this->hasOne(Parameter::class, ['id' => 'parameter_id']);
    }

}