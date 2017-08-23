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
use application\repositories\ItemRepository;
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
    private $itemRepository;

    /**
     * LabelService constructor.
     * @param LabelRepository $labelRepository
     * @param ItemRepository $itemRepository
     */
    public function __construct(LabelRepository $labelRepository, ItemRepository $itemRepository)
    {
        $this->labelRepository = $labelRepository;
        $this->itemRepository = $itemRepository;
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
        if ($this->itemRepository->bindedWithLabel($label->id)) {
            throw new \DomainException('Cant delete label with items');
        }
        $this->labelRepository->remove($label);
    }
}