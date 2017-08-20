<?php
/**
 * Created by PhpStorm.
 * User: odd
 * Date: 20.08.2017
 * Time: 16:12
 */

namespace application\services\Items;


use application\forms\Items\OverviewEditForm;
use application\repositories\ItemRepository;

class OverviewService
{
    private $itemRepository;

    public function __construct(ItemRepository $itemRepository)
    {
        $this->itemRepository = $itemRepository;
    }

    public function edit($id, $reviewId, OverviewEditForm $form) : void
    {
        $item = $this->itemRepository->get($id);
        $item->editOverview($reviewId, $form->vote, $form->text);
        $this->itemRepository->save($item);
    }

    public function accept($id, $reviewId) : void
    {
        $item = $this->itemRepository->get($id);
        $item->acceptOverview($reviewId);
        $this->itemRepository->save($item);
    }

    public function decline($id, $reviewId) : void
    {
        $item = $this->itemRepository->get($id);
        $item->declineOverview($reviewId);
        $this->itemRepository->save($item);
    }

    public function delete($id, $reviewId) : void
    {
        $item = $this->itemRepository->get($id);
        $item->deleteOverview($reviewId);
        $this->itemRepository->save($item);
    }
}