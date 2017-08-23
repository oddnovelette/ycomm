<?php
/**
 * Created by PhpStorm.
 * User: odd
 * Date: 19.08.2017
 * Time: 17:31
 */

namespace application\forms\Items;

use application\forms\MetatagsForm;
use application\forms\FormsMerger;
use application\models\Items\Category;
use yii\helpers\ArrayHelper;

/**
 * Class CategoryForm
 * @package application\forms\Items
 */
class CategoryForm extends FormsMerger
{
    public $name;
    public $slug;
    public $title;
    public $description;
    public $parentId;
    /**
     * @var Category
     */
    private $_category;

    /**
     * CategoryForm constructor.
     * @param Category|null $category
     * @param array|null $config
     */
    public function __construct(Category $category = null, array $config = null)
    {
        if ($category) {
            $this->name = $category->name;
            $this->slug = $category->slug;
            $this->title = $category->title;
            $this->description = $category->description;
            $this->parentId = $category->parent ? $category->parent->id : null;
            $this->meta = new MetatagsForm($category->meta);
            $this->_category = $category;
        } else {
            $this->meta = new MetatagsForm();
        }
        parent::__construct($config);
    }

    /**
     * @return array
     */
    public function rules() : array
    {
        return [
            [['name', 'slug'], 'required'],
            [['parentId'], 'integer'],
            [['name', 'slug', 'title'], 'string', 'max' => 255],
            [['description'], 'string'],
            ['slug', 'match', 'pattern' => '#^[a-z0-9_-]*$#s'],
            [['name', 'slug'], 'unique', 'targetClass' => Category::class,
            'filter' => $this->_category ? ['<>', 'id', $this->_category->id] : null]
        ];
    }

    public function treeListSort() : array
    {
        return ArrayHelper::map(Category::find()->orderBy('lft')->asArray()->all(), 'id', function (array $category) {
            return ($category['depth'] > 1 ? str_repeat('-- ', $category['depth'] - 1) . ' ' : '') . $category['name'];
        });
    }

    /**
     * @return array
     */
    public function internalForms() : array
    {
        return ['meta'];
    }
}