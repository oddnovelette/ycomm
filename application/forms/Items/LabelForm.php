<?php
/**
 * Created by PhpStorm.
 * User: odd
 * Date: 19.08.2017
 * Time: 04:09
 */

namespace application\forms\Items;

use application\forms\FormsMerger;
use application\forms\MetatagsForm;
use application\models\Items\Label;

/**
 * Class LabelForm
 * @package application\forms\Items
 */
class LabelForm extends FormsMerger
{
    public $name;
    public $slug;

    /**
     * @var Label|null
     */
    private $_label = null;

    /**
     * LabelForm constructor.
     * @param Label|null $label
     * @param array|null $config
     */
    public function __construct(Label $label = null, array $config = null)
    {
        if ($label) {
            $this->name = $label->name;
            $this->slug = $label->slug;
            $this->meta = new MetatagsForm($label->meta);
            $this->_label = $label;
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
            [['name'], 'required'],
            [['name', 'slug'], 'string', 'max' => 255],
            ['slug', 'match', 'pattern' => '#^[a-z0-9_-]*$#s'],
            [['name', 'slug'], 'unique', 'targetClass' => Label::class,
            'filter' => $this->_label ? ['<>', 'id', $this->_label->id] : null]
        ];
    }

    /**
     * @return array
     */
    public function internalForms() : array
    {
        return ['meta'];
    }
}