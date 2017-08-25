<?php
/**
 * Created by PhpStorm.
 * User: odd
 * Date: 19.08.2017
 * Time: 19:43
 */

namespace application\forms\Items;

use application\models\Items\{Category, Item};

use yii\base\Model;
use yii\helpers\ArrayHelper;

/**
 * Class CategoriesForm
 * @package application\forms\Items
 */
class CategoriesForm extends Model
{
    public $main;
    public $others = array();

    /**
     * CategoriesForm constructor.
     * @param Item|null $item
     * @param array|null $config
     */
    public function __construct(Item $item = null, array $config = null)
    {
        if ($item) {
            $this->main = $item->category_id;
            $this->others = ArrayHelper::getColumn($item->categoryAttachments, 'category_id');
        }
        parent::__construct($config);
    }

    /**
     * @return array
     */
    public function rules() : array
    {
        return [
            ['main', 'required'],
            ['main', 'integer'],
            ['others', 'each', 'rule' => ['integer']],
            ['others', 'default', 'value' => []],
        ];
    }

    public function categoriesList() : array
     {
         return ArrayHelper::map(Category::find()
             ->andWhere(['>', 'depth', 0])
             ->orderBy('lft')->asArray()
             ->all(), 'id', function (array $category) {
             return ($category['depth'] > 1 ? str_repeat('-- ', $category['depth'] - 1) . ' ' : '') . $category['name'];
         });
     }
}