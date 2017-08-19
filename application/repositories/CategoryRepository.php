<?php
/**
 * Created by PhpStorm.
 * User: odd
 * Date: 19.08.2017
 * Time: 18:00
 */

namespace application\repositories;

use application\models\Items\Category;

/**
 * Class CategoryRepository
 * @package application\repositories
 */
class CategoryRepository
{
    /**
     * @param $id
     * @return Category
     */
    public function get($id) : Category
    {
        if (!$category = Category::findOne($id)) throw new \DomainException('Category is not found.');
        return $category;
    }

    /**
     * @param Category $category
     * @return void
     */
    public function save(Category $category) : void
    {
        if (!$category->save()) throw new \RuntimeException('Saving error.');
    }

    /**
     * @param Category $category
     * @return void
     */
    public function remove(Category $category) : void
    {
        if (!$category->delete()) throw new \RuntimeException('Removing error.');
    }
}