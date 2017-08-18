<?php
/**
 * Created by PhpStorm.
 * User: odd
 * Date: 18.08.2017
 * Time: 18:41
 */

namespace application\repositories;

use application\models\Items\Tag;
use yii\web\NotFoundHttpException;

/**
 * Class TagRepository
 * @package application\repositories
 */
class TagRepository
{
    /**
     * @param $id
     * @return Tag
     * @throws NotFoundHttpException
     */
    public function get($id) : Tag
    {
        if (!$tag = Tag::findOne($id)) throw new NotFoundHttpException('Tag is not found');
        return $tag;
    }

    /**
     * @param $name
     * @return Tag|null
     */
    public function findByName($name) : ? Tag
    {
        return Tag::findOne(['name' => $name]);
    }

    /**
     * @param Tag $tag
     * @return void
     */
    public function save(Tag $tag) : void
    {
        if (!$tag->save()) throw new \RuntimeException('Saving error.');
    }

    /**
     * @param Tag $tag
     * @return void
     */
    public function remove(Tag $tag) : void
    {
        if (!$tag->delete()) throw new \RuntimeException('Removing error.');
    }
}