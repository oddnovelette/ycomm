<?php
/**
 * Created by PhpStorm.
 * User: odd
 * Date: 19.08.2017
 * Time: 19:32
 */

namespace application\forms\Items;

use application\models\Items\Item;
use yii\base\Model;
use yii\helpers\ArrayHelper;

/**
 * Class TagsForm
 * @package application\forms\Items
 */
class TagsForm extends Model
{
    public $existing = [];
    public $textNew;

    /**
     * TagsForm constructor.
     * @param Item|null $item
     * @param array|null $config
     */
    public function __construct(Item $item = null, array $config = null)
    {
        if ($item) {
            $this->existing = ArrayHelper::getColumn($item->tagAttachments, 'tag_id');
        }
        parent::__construct($config);
    }

    public function rules() : array
    {
        return [
            ['existing', 'each', 'rule' => ['integer']],
            ['existing', 'default', 'value' => []],
            ['textNew', 'string'],
        ];
    }

    public function getNewNames() : array
    {
        return array_filter(array_map('trim', preg_split('#\s*,\s*#i', $this->textNew)));
    }
}