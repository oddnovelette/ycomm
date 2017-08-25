<?php
/**
 * Created by PhpStorm.
 * User: odd
 * Date: 25.08.2017
 * Time: 04:08
 */

namespace application\models\Items;

use yii\db\ActiveRecord;

/**
 * @property int $id
 * @property string $code
 * @property string $name
 * @property string $price
 *
 * Class Variation
 * @package application\models\Items
 */
class Variation extends ActiveRecord
{
    /**
     * @param $code
     * @param $name
     * @param $price
     * @return Variation
     */
    public static function create($code, $name, $price) : self
    {
        $variation = new self();
        $variation->code = $code;
        $variation->name = $name;
        $variation->price = $price;
        return $variation;
    }

    /**
     * @param $code
     * @param $name
     * @param $price
     */
    public function edit($code, $name, $price) : void
    {
        $this->code = $code;
        $this->name = $name;
        $this->price = $price;
    }

    /**
     * @param $id
     * @return bool
     */
    public function isIdEqualTo($id)
    {
        return $this->id == $id;
    }

    /**
     * @param $code
     * @return bool
     */
    public function isCodeEqualTo($code)
    {
        return $this->code === $code;
    }

    public static function tableName() : string
    {
        return '{{%item_variations}}';
    }
}