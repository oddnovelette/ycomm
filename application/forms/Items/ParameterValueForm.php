<?php
/**
 * Created by PhpStorm.
 * User: odd
 * Date: 20.08.2017
 * Time: 15:53
 */

namespace application\forms\Items;

use application\models\Items\Parameter;
use application\models\Items\ParameterValue;
use yii\base\Model;

class ParameterValueForm extends Model
{
    public $value;
    private $_parameter;

    /**
     * ParameterValueForm constructor.
     * @param Parameter $parameter
     * @param ParameterValue|null $value
     * @param array|null $config
     */
    public function __construct(Parameter $parameter, ParameterValue $value = null, array $config = null)
    {
        if ($value) $this->value = $value->value;
        $this->_parameter = $parameter;
        parent::__construct($config);
    }

    public function rules() : array
    {
        return array_filter([
            $this->_parameter->required ? ['value', 'required'] : false,
            $this->_parameter->isString() ? ['value', 'string', 'max' => 255] : false,
            $this->_parameter->isInteger() ? ['value', 'integer'] : false,
            $this->_parameter->isFloat() ? ['value', 'number'] : false,
            ['value', 'safe'],
        ]);
    }

    public function attributeLabels() : array
    {
        return [
            'value' => $this->_parameter->name,
        ];
    }

    public function variantsList() : ? array
    {
       return $this->_parameter->variants
           ? array_combine($this->_parameter->variants, $this->_parameter->variants)
           : null;
    }

    public function getId() : int
    {
        return $this->_parameter->id;
    }
}