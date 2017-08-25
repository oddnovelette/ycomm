<?php
/**
 * Created by PhpStorm.
 * User: odd
 * Date: 20.08.2017
 * Time: 16:02
 */

namespace application\forms\Items;

use application\forms\{FormsMerger, MetatagsForm};

use application\models\Items\{Item, Label, Parameter};

use yii\helpers\ArrayHelper;

/**
 * Class ItemCreateForm
 * @package application\forms\Items
 */
class ItemCreateForm extends FormsMerger
{
    public $labelId;
    public $code;
    public $name;
    public $text;

    /**
     * ItemCreateForm constructor.
     * @param array|null $config
     */
    public function __construct(array $config = null)
    {
        $this->price = new PriceForm();
        $this->meta = new MetatagsForm();
        $this->categories = new CategoriesForm();
        $this->images = new ImageForm();
        $this->tags = new TagsForm();
        $this->values = array_map(function (Parameter $parameter) {
            return new ParameterValueForm($parameter);
        },
            Parameter::find()->orderBy('sort')->all());
        parent::__construct($config);
    }

    public function rules() : array
    {
        return [
            [['labelId', 'code', 'name'], 'required'],
            [['code', 'name'], 'string', 'max' => 255],
            [['labelId'], 'integer'],
            [['code'], 'unique', 'targetClass' => Item::class],
            ['text', 'string'],
        ];
    }

    /**
     * @return array
     */
    public function labelsList() : array
    {
        return ArrayHelper::map(Label::find()->orderBy('name')->asArray()->all(), 'id', 'name');
    }

    /**
     * @return array
     */
    protected function internalForms() : array
    {
        return ['price', 'meta', 'images', 'categories', 'tags', 'values'];
    }
}