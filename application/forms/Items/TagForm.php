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
 * Class TagSearch
 * @package application\forms\Items
 */
class TagForm extends Model
{
    public $name;
    public $slug;

    /**
     * @var Tag
     */
    private $_tag;

    /**
     * TagSearch constructor.
     * @param Tag|null $tag
     * @param array|null $config
     */
    public function __construct(Tag $tag = null, array $config = null)
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
            [['name'], 'required'],
            [['name', 'slug'], 'string', 'max' => 255],
            ['slug', 'match', 'pattern' => '#^[a-z0-9_-]*$#s'],
            [['name', 'slug'], 'unique', 'targetClass' => Tag::class,
            'filter' => $this->_tag ? ['<>', 'id', $this->_tag->id] : null]
        ];
    }
}