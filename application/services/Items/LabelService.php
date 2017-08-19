<?php
/**
 * Created by PhpStorm.
 * User: odd
 * Date: 19.08.2017
 * Time: 04:49
 */

namespace application\services\Items;

use application\forms\Items\LabelForm;
use application\models\Items\{Label, Meta};
use application\repositories\LabelRepository;
use yii\helpers\Inflector;

/**
 * Class LabelService
 * @package application\services\Items
 */
class LabelService
{
    /**
     * @var LabelRepository
     */
    private $labelRepository;

    /**
     * LabelService constructor.
     * @param LabelRepository $labelRepository
     */
    public function __construct(LabelRepository $labelRepository)
    {
        $this->labelRepository = $labelRepository;
    }

    /**
     * @param LabelForm $form
     * @return Label
     */
    public function create(LabelForm $form) : Label
    {
        $label = Label::create(
            $form->name,
            $form->slug ?: Inflector::slug($form->name),
            new Meta($form->meta->title, $form->meta->description, $form->meta->keywords)
        );
        $this->labelRepository->save($label);
        return $label;
    }

    /**
     * @param int $id
     * @param LabelForm $form
     * @return void
     */
    public function edit(int $id, LabelForm $form) : void
    {
        $label = $this->labelRepository->get($id);
        $label->edit(
            $form->name,
            $form->slug ?: Inflector::slug($form->name),
            new Meta($form->meta->title, $form->meta->description, $form->meta->keywords)
        );
        $this->labelRepository->save($label);
    }

    /**
     * @param int $id
     * @return void
     */
    public function remove(int $id) : void
    {
        $label = $this->labelRepository->get($id);
        $this->labelRepository->remove($label);
    }
}