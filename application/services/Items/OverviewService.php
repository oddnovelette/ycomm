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

/**
 * Class OverviewService
 * @package application\services\Items
 */
class OverviewService
{
    /**
     * @var ItemRepository
     */
    private $itemRepository;

    /**
     * OverviewService constructor.
     * @param ItemRepository $itemRepository
     */
    public function __construct(ItemRepository $itemRepository)
    {
        $this->itemRepository = $itemRepository;
    }

    /**
     * @param int $id
     * @param int $reviewId
     * @param OverviewEditForm $form
     */
    public function edit(int $id, int $reviewId, OverviewEditForm $form) : void
    {
        $item = $this->itemRepository->get($id);
        $item->editOverview($reviewId, $form->vote, $form->text);
        $this->itemRepository->save($item);
    }

    /**
     * @param int $id
     * @param int $reviewId
     * @return void
     */
    public function accept(int $id, int $reviewId) : void
    {
        $item = $this->itemRepository->get($id);
        $item->acceptOverview($reviewId);
        $this->itemRepository->save($item);
    }

    /**
     * @param int $id
     * @param int $reviewId
     * @return void
     */
    public function decline(int $id, int $reviewId) : void
    {
        $item = $this->itemRepository->get($id);
        $item->declineOverview($reviewId);
        $this->itemRepository->save($item);
    }

    /**
     * @param int $id
     * @param int $reviewId
     * @return void
     */
    public function delete(int $id, int $reviewId) : void
    {
        $item = $this->itemRepository->get($id);
        $item->deleteOverview($reviewId);
        $this->itemRepository->save($item);
    }
}