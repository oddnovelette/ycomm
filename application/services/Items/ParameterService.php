<?php
/**
 * Created by PhpStorm.
 * User: odd
 * Date: 20.08.2017
 * Time: 16:10
 */

namespace application\services\Items;

use application\forms\Items\ParametersForm;
use application\models\Items\Parameter;
use application\repositories\ParameterRepository;

/**
 * Class ParameterService
 * @package application\services\Items
 */
class ParameterService
{
    /**
     * @var ParameterRepository
     */
    private $parameterRepository;

    /**
     * ParameterService constructor.
     * @param ParameterRepository $parameterRepository
     */
    public function __construct(ParameterRepository $parameterRepository)
    {
        $this->parameterRepository = $parameterRepository;
    }

    /**
     * @param ParametersForm $form
     * @return Parameter
     */
    public function create(ParametersForm $form) : Parameter
    {
        $parameter = Parameter::create(
            $form->name,
            $form->type,
            $form->required,
            $form->default,
            $form->variants,
            $form->sort
        );
        $this->parameterRepository->save($parameter);
        return $parameter;
    }

    /**
     * @param int $id
     * @param ParametersForm $form
     * @return void
     */
    public function edit(int $id, ParametersForm $form) : void
    {
        $parameter = $this->parameterRepository->get($id);
        $parameter->edit(
            $form->name,
            $form->type,
            $form->required,
            $form->default,
            $form->variants,
            $form->sort
        );
        $this->parameterRepository->save($parameter);
    }

    /**
     * @param int $id
     * @return void
     */
    public function remove(int $id) : void
    {
        $parameter = $this->parameterRepository->get($id);
        $this->parameterRepository->remove($parameter);
    }
}