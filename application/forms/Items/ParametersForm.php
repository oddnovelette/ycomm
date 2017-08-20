<?php
/**
 * Created by PhpStorm.
 * User: odd
 * Date: 20.08.2017
 * Time: 16:08
 */

namespace application\forms\Items;


use application\models\Items\Parameter;
use yii\base\Model;

class ParametersForm extends Model
{
    public $name;
    public $type;
    public $required;
    public $default;
    public $textVariants;
    public $sort;

    private $_parameter;

    public function __construct(Parameter $parameter = null, array $config = null)
    {
        if ($parameter) {
            $this->name = $parameter->name;
            $this->type = $parameter->type;
            $this->required = $parameter->required;
            $this->default = $parameter->default;
            $this->textVariants = implode(PHP_EOL, $parameter->variants);
            $this->sort = $parameter->sort;
            $this->_parameter = $parameter;
        } else {
            $this->sort = Parameter::find()->max('sort') + 1;
        }
        parent::__construct($config);
    }

    public function rules() : array
    {
        return [
            [['name', 'type', 'sort'], 'required'],
            [['required'], 'boolean'],
            [['default'], 'string', 'max' => 255],
            [['textVariants'], 'string'],
            [['sort'], 'integer'],
            [['name'], 'unique', 'targetClass' => Parameter::class,
            'filter' => $this->_parameter ? ['<>', 'id', $this->_parameter->id] : null]
        ];
    }

    public function getVariants() : array
    {
        return preg_split('#\s+#i', $this->textVariants);
    }
}