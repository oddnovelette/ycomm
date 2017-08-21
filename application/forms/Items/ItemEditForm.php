<?php
/**
 * Created by PhpStorm.
 * User: odd
 * Date: 20.08.2017
 * Time: 16:00
 */

namespace application\forms\Items;

use application\forms\{FormsMerger, MetatagsForm};
use application\models\Items\{Item, Parameter};

/**
 * Class ItemEditForm
 * @package application\forms\Items
 */
class ItemEditForm extends FormsMerger
{
    public $labelId;
    public $code;
    public $name;

    private $_item;

    /**
     * ItemEditForm constructor.
     * @param Item $item
     * @param array|null $config
     */
    public function __construct(Item $item, array $config = null)
    {
        $this->labelId = $item->label_id;
        $this->code = $item->code;
        $this->name = $item->name;
        $this->meta = new MetatagsForm($item->meta);
        $this->tags = new TagsForm($item);
        $this->values = array_map(
            function (Parameter $parameter) use ($item) {
            return new ParameterValueForm($parameter, $item->getParameterValue($parameter->id));
        },
            Parameter::find()->orderBy('sort')->all());
            $this->_item = $item;
        parent::__construct($config);
    }

    /**
     * @return array
     */
    public function rules() : array
    {
        return [
            [['brandId', 'code', 'name'], 'required'],
            [['brandId'], 'integer'],
            [['code', 'name'], 'string', 'max' => 255],
            [['code'], 'unique', 'targetClass' => Item::class,
            'filter' => $this->_item ? ['<>', 'id', $this->_item->id] : null],
        ];
    }

    /**
     * @return array
     */
    protected function internalForms() : array
    {
        return ['meta', 'tags', 'values'];
    }
}