<?php
/**
 * Created by PhpStorm.
 * User: odd
 * Date: 20.08.2017
 * Time: 16:10
 */

namespace application\services\Items;

use application\models\Items\{Item, Tag, Meta};

use application\forms\Items\{
    ItemEditForm,
    ItemCreateForm,
    CategoriesForm,
    ImageForm
};
use application\repositories\{
    CategoryRepository,
    ItemRepository,
    LabelRepository,
    TagRepository
};

/**
 * Class ItemService
 * @package application\services\Items
 */
class ItemService
{
    private $itemRepository;
    private $labelRepository;
    private $categoryRepository;
    private $tagRepository;

    /**
     * ItemService constructor.
     * @param ItemRepository $itemRepository
     * @param LabelRepository $labelRepository
     * @param CategoryRepository $categoryRepository
     * @param TagRepository $tagRepository
     */
    public function __construct
    (
        ItemRepository $itemRepository,
        LabelRepository $labelRepository,
        CategoryRepository $categoryRepository,
        TagRepository $tagRepository

    )
    {
        $this->itemRepository = $itemRepository;
        $this->labelRepository = $labelRepository;
        $this->categoryRepository = $categoryRepository;
        $this->tagRepository = $tagRepository;
    }

    /**
     * @param ItemCreateForm $form
     * @return Item
     */
    public function create(ItemCreateForm $form) : Item
    {
        $label = $this->labelRepository->get($form->labelId);
        $category = $this->categoryRepository->get($form->categories->main);

        $item = Item::create($label->id, $category->id, $form->code, $form->name,

            new Meta($form->meta->title, $form->meta->description, $form->meta->keywords)
        );
        $item->setPrice($form->price->new, $form->price->old);

        // Attaching loops for attach item-related functionality

        foreach ($form->categories->others as $otherId) {
            $category = $this->categoryRepository->get($otherId);
            $item->attachCategory($category->id);
        }
        foreach ($form->values as $value) {
            $item->setParameterValue($value->id, $value->value);
        }
        foreach ($form->images->files as $file) {
            $item->uploadImage($file);
        }
        foreach ($form->tags->existing as $tagId) {
            $tag = $this->tagRepository->get($tagId);
            $item->attachTag($tag->id);
        }

        \Yii::$app->db->transaction(function () use ($item, $form) {
            foreach ($form->tags->newNames as $tagName) {
                if (!$tag = $this->tagRepository->findByName($tagName)) {
                    $tag = Tag::create($tagName, $tagName);
                    $this->tagRepository->save($tag);
                }
                $item->attachTag($tag->id);
            }
            $this->itemRepository->save($item);
        });
        return $item;
    }

    /**
     * @param int $id
     * @param ItemEditForm $form
     * @return void
     */
    public function edit(int $id, ItemEditForm $form) : void
    {
        $item = $this->itemRepository->get($id);
        $label = $this->labelRepository->get($form->labelId);

        $item->edit($label->id, $form->code, $form->name,
            new Meta($form->meta->title, $form->meta->description, $form->meta->keywords)
        );
        foreach ($form->values as $value) {
            $item->setParameterValue($value->id, $value->value);
        }
        $item->detachItemTags();
        foreach ($form->tags->existing as $tagId) {
            $tag = $this->tagRepository->get($tagId);
            $item->attachTag($tag->id);
        }

        \Yii::$app->db->transaction(function () use ($item, $form) {
            foreach ($form->tags->newNames as $tagName) {
                if (!$tag = $this->tagRepository->findByName($tagName)) {
                    $tag = Tag::create($tagName, $tagName);
                    $this->tagRepository->save($tag);
                }
                $item->attachTag($tag->id);
            }
            $this->itemRepository->save($item);
        });
    }

    /**
     * @param int $id
     * @param CategoriesForm $form
     * @return void
     */
    public function changeCategories(int $id, CategoriesForm $form) : void
    {
        $item = $this->itemRepository->get($id);
        $category = $this->categoryRepository->get($form->main);
        $item->changeMainCategory($category->id);
        $item->detachItemCategories();
        foreach ($form->others as $otherId) {
            $category = $this->categoryRepository->get($otherId);
            $item->attachCategory($category->id);
        }
        $this->itemRepository->save($item);
    }

    /**
     * @param int $id
     * @param ImageForm $form
     * @return void
     */
    public function uploadImages(int $id, ImageForm $form) : void
    {
        $item = $this->itemRepository->get($id);
        foreach ($form->files as $file) {
            $item->uploadImage($file);
        }
        $this->itemRepository->save($item);
    }

    /**
     * @param int $id
     * @param int $imageId
     * @return void
     */
    public function moveImageUp(int $id, int $imageId) : void
    {
        $item = $this->itemRepository->get($id);
        $item->moveImageUp($imageId);
        $this->itemRepository->save($item);
    }

    /**
     * @param int $id
     * @param int $photoId
     * @return void
     */
    public function moveImageDown(int $id, int $photoId) : void
    {
        $item = $this->itemRepository->get($id);
        $item->moveImageDown($photoId);
        $this->itemRepository->save($item);
    }

    /**
     * @param int $id
     * @param int $photoId
     * @return void
     */
    public function deleteImage(int $id, int $photoId) : void
    {
        $item = $this->itemRepository->get($id);
        $item->deleteImage($photoId);
        $this->itemRepository->save($item);
    }

    /**
     * @param int $id
     * @param int $otherId
     * @return void
     */
    public function attachRelatedItem(int $id, int $otherId) : void
    {
        $item = $this->itemRepository->get($id);
        $other = $this->itemRepository->get($otherId);
        $item->attachRelatedItem($other->id);
        $this->itemRepository->save($item);
    }

    /**
     * @param int $id
     * @param int $otherId
     * @return void
     */
    public function removeRelatedItem(int $id, int $otherId) : void
    {
        $item = $this->itemRepository->get($id);
        $other = $this->itemRepository->get($otherId);
        $item->revokeRelatedItem($other->id);
        $this->itemRepository->save($item);
    }

    /**
     * @param int $id
     * @return void
     */
    public function remove(int $id) : void
    {
        $item = $this->itemRepository->get($id);
        $this->itemRepository->remove($item);
    }
}