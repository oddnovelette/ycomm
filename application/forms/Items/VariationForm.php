<?php
/**
 * Created by PhpStorm.
 * User: odd
 * Date: 25.08.2017
 * Time: 04:53
 */

namespace application\forms\Items;

use application\models\Items\Variation;
use yii\base\Model;

/**
 * Class VariationForm
 * @package application\forms\Items
 */
class VariationForm extends Model
{
    public $code;
    public $name;
    public $price;

    /**
     * VariationForm constructor.
     * @param Variation|null $variation
     * @param array|null $config
     */
    public function __construct(Variation $variation = null, array $config = null)
    {
        if ($variation) {
            $this->code = $variation->code;
            $this->name = $variation->name;
            $this->price = $variation->price;
        }
        parent::__construct($config);
    }

    public function rules() : array
    {
        return [
            [['code', 'name'], 'required'],
            [['price'], 'integer'],
        ];
    }
}