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

class ParameterService
{
    private $parameterRepository;

    public function __construct(ParameterRepository $parameterRepository)
    {
        $this->parameterRepository = $parameterRepository;
    }

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

    public function edit($id, ParametersForm $form) : void
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

    public function remove($id) : void
    {
        $parameter = $this->parameterRepository->get($id);
        $this->parameterRepository->remove($parameter);
    }
}