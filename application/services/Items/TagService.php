<?php
namespace application\services\Items;

use application\models\Items\Tag;
use application\forms\Items\TagForm;
use application\repositories\TagRepository;
use yii\helpers\Inflector;

/**
 * Class TagService
 * @package application\services\Items
 */
class TagService
{
    private $tagRepository;

    /**
     * TagService constructor.
     * @param TagRepository $tagRepository
     */
    public function __construct(TagRepository $tagRepository)
    {
        $this->tagRepository = $tagRepository;
    }

    /**
     * Creates tags with auto-slug
     *
     * @param TagForm $form
     * @return Tag
     */
    public function create(TagForm $form) : Tag
    {
        $tag = Tag::create($form->name, $form->slug ?: Inflector::slug($form->name));
        $this->tagRepository->save($tag);
        return $tag;
    }

    /**
     * Edit tags with auto-slug
     *
     * @param int $id
     * @param TagForm $form
     * @return void
     */
    public function edit(int $id, TagForm $form) : void
    {
        $tag = $this->tagRepository->get($id);
        $tag->edit($form->name, $form->slug ?: Inflector::slug($form->name));
        $this->tagRepository->save($tag);
    }

    /**
     * @param int $id
     * @return void
     */
    public function remove(int $id) : void
    {
        $tag = $this->tagRepository->get($id);
        $this->tagRepository->remove($tag);
    }
}