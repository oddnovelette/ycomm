<?php
/**
 * Created by PhpStorm.
 * User: odd
 * Date: 20.08.2017
 * Time: 16:27
 */

namespace application\repositories;

use application\models\Items\Parameter;

/**
 * Class ParameterRepository
 * @package application\repositories
 */
class ParameterRepository
{
    /**
     * @param $id
     * @return Parameter
     */
    public function get($id) : Parameter
    {
        if (!$parameter = Parameter::findOne($id)) {
            throw new \DomainException('Parameter not found');
        }
        return $parameter;
    }

    /**
     * @param Parameter $parameter
     * @return void
     */
    public function save(Parameter $parameter) : void
    {
        if (!$parameter->save()) throw new \RuntimeException('Saving fault');
    }

    /**
     * @param Parameter $parameter
     * @return void
     */
    public function remove(Parameter $parameter) : void
    {
        if (!$parameter->delete()) throw new \RuntimeException('Deleting fault');
    }
}