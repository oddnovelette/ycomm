<?php
/**
 * Created by PhpStorm.
 * User: odd
 * Date: 18.08.2017
 * Time: 18:12
 */
namespace application\forms\Items;

use application\models\Items\Tag;
use yii\base\Model;

/**
 * Class TagForm
 * @package application\forms\Items
 */
class TagForm extends Model
{
    public $name;
    public $slug;
    private $_tag;

    /**
     * TagForm constructor.
     * @param Tag $tag
     * @param array|null $config
     */
    public function __construct(Tag $tag, array $config = null)
    {
        if ($tag) {
            $this->name = $tag->name;
            $this->slug = $tag->slug;
            $this->_tag = $tag;
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
            [['name', 'slug'], 'string', 'max' => 255],
            ['slug', 'match', 'pattern' => '#^[a-z0-9_-]*$#s'],
            [['name', 'slug'], 'unique', 'targetClass'
            => Tag::class, 'filter' => $this->_tag ? ['<>', 'id', $this->_tag->id] : null]
        ];
    }
}