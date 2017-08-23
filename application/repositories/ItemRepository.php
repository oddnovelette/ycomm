<?php
/**
 * Created by PhpStorm.
 * User: odd
 * Date: 20.08.2017
 * Time: 16:28
 */

namespace application\repositories;

use application\models\Items\Item;

/**
 * Class ItemRepository
 * @package application\repositories
 */
class ItemRepository
{
    /**
     * @param $id
     * @return Item
     */
    public function get($id) : Item
    {
        if (!$item = Item::findOne($id)) throw new \RuntimeException('Item not found.');
        return $item;
    }

    /**
     * @param Item $item
     */
    public function save(Item $item) : void
    {
        if (!$item->save()) throw new \RuntimeException('Saving fault');
    }

    /**
     * @param Item $item
     */
    public function remove(Item $item) : void
    {
        if (!$item->delete()) throw new \RuntimeException('Deleting fault');
    }

    /**
     * @param $id
     * @return bool
     */
    public function bindedWithLabel($id) : bool
    {
        return Item::find()->andWhere(['label_id' => $id])->exists();
    }

    /**
     * @param $id
     * @return bool
     */
    public function bindedWithCategory($id) : bool
    {
        return Item::find()->andWhere(['category_id' => $id])->exists();
    }
}