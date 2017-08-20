<?php
/**
 * Created by PhpStorm.
 * User: odd
 * Date: 19.08.2017
 * Time: 19:36
 */

namespace application\forms\Items;
use application\forms\MetatagsForm;
use application\models\Items\Item;
use yii\base\Model;

/**
 * @property MetatagsForm $meta
 * @property CategoriesForm $categories
 * @property TagsForm $tags
 * @property ParameterValueForm[] $values
 *
 * Class PriceForm
 * @package application\forms\Items
 */
class PriceForm extends Model
{
    public $old;
    public $new;

    public function __construct(Item $item = null, array $config = null)
    {
        if ($item) {
            $this->new = $item->price_new;
            $this->old = $item->price_old;
        }
        parent::__construct($config);
    }

    public function rules() : array
    {
        return [
            [['new'], 'required'],
            [['old', 'new'], 'integer', 'min' => 0],
        ];
    }
}