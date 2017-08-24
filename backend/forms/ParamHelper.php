<?php
/**
 * Created by PhpStorm.
 * User: odd
 * Date: 23.08.2017
 * Time: 18:27
 */

namespace backend\forms;

use application\models\Items\Parameter;
use yii\helpers\ArrayHelper;

/**
 * Class ParamHelper
 * @package backend\forms
 */
class ParamHelper
{
    public static function typeList() : array
    {
        return [
            Parameter::TYPE_STRING => 'String',
            Parameter::TYPE_INTEGER => 'Integer number',
            Parameter::TYPE_FLOAT => 'Float number',
        ];
    }

    /**
     * @param string $type
     * @return string
     */
    public static function typeName(string $type) : string
    {
        return ArrayHelper::getValue(self::typeList(), $type);
    }
}