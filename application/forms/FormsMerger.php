<?php
/**
 * Created by PhpStorm.
 * User: odd
 * Date: 19.08.2017
 * Time: 04:19
 */

namespace application\forms;

use yii\base\Model;
use yii\helpers\ArrayHelper;

/**
 * Class FormsMerger
 * @package application\forms
 */
abstract class FormsMerger extends Model
{
    /**
    * @var Model[]|array[]
    */
    private $forms = [];

    /**
     * @return array
     */
    abstract protected function internalForms() : array;

    /**
     * @param array $data
     * @param null $formName
     * @return bool
     */
    public function load($data, $formName = null) : bool
    {
        $success = parent::load($data, $formName);
        foreach ($this->forms as $name => $form) {
            if (is_array($form)) {
                $success = Model::loadMultiple($form, $data, $formName === null ? null : $name) && $success;
            } else {
                $success = $form->load($data, $formName !== '' ? null : $name) && $success;
            }
        }
        return $success;
    }

    /**
     * @param null $attributeNames
     * @param bool $clearErrors
     * @return bool
     */
    public function validate($attributeNames = null, $clearErrors = true) : bool
    {
        $parentNames = $attributeNames !== null ? array_filter((array)$attributeNames, 'is_string') : null;
        $success = parent::validate($parentNames, $clearErrors);
        foreach ($this->forms as $name => $form) {
            if (is_array($form)) {
                $success = Model::validateMultiple($form) && $success;
            } else {
                $innerNames = $attributeNames !== null ? ArrayHelper::getValue($attributeNames, $name) : null;
                $success = $form->validate($innerNames ?: null, $clearErrors) && $success;
            }
        }
        return $success;
    }

    /**
     * @param string $name
     * @return array|mixed|Model
     */
    public function __get($name) : array
    {
        if (isset($this->forms[$name])) {
            return $this->forms[$name];
        }
        return parent::__get($name);
    }

    /**
     * @param string $name
     * @param mixed $value
     * @return void
     */
    public function __set($name, $value) : void
    {
        if (in_array($name, $this->internalForms(), true)) {
            $this->forms[$name] = $value;
        } else {
            parent::__set($name, $value);
        }
    }

    /**
     * @param string $name
     * @return bool
     */
    public function __isset($name) : bool
    {
        return isset($this->forms[$name]) || parent::__isset($name);
    }
}