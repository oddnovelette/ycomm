<?php
/**
 * Created by PhpStorm.
 * User: odd
 * Date: 19.08.2017
 * Time: 17:59
 */

namespace application\services\Items;

use application\forms\Items\CategoryForm;
use application\models\Items\{Category, Meta};
use application\repositories\CategoryRepository;

/**
 * Class CategoryService
 * @package application\services\Items
 */
class CategoryService
{
    /**
     * @var CategoryRepository
     */
    private $categoryRepository;

    /**
     * CategoryService constructor.
     * @param CategoryRepository $categoryRepository
     */
    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * @param CategoryForm $form
     * @return Category
     */
    public function create(CategoryForm $form) : Category
    {
        $parent = $this->categoryRepository->get($form->parentId);
        $category = Category::create
        (
            $form->name,
            $form->slug,
            $form->title,
            $form->description,
            new Meta($form->meta->title, $form->meta->description, $form->meta->keywords)
        );
        $category->appendTo($parent);
        $this->categoryRepository->save($category);
        return $category;
    }

    /**
     * @param int $id
     * @param CategoryForm $form
     * @return void
     */
    public function edit(int $id, CategoryForm $form) : void
    {
        $category = $this->categoryRepository->get($id);
        if ($category->isRoot()) throw new \DomainException('Unable to handle the root');
        $category->edit
        (
            $form->name,
            $form->slug,
            $form->title,
            $form->description,
            new Meta($form->meta->title, $form->meta->description, $form->meta->keywords)
        );

        if ($form->parentId !== $category->parent->id) {
            $parent = $this->categoryRepository->get($form->parentId);
            $category->appendTo($parent);
        }
        $this->categoryRepository->save($category);
    }

    /**
     * @param int $id
     * @return void
     */
    public function remove(int $id) : void
    {
        $category = $this->categoryRepository->get($id);
        if ($category->isRoot()) throw new \DomainException('Unable to handle the root');
        $this->categoryRepository->remove($category);
    }
}