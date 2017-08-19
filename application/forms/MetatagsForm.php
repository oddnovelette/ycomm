<?php
/**
 * Created by PhpStorm.
 * User: odd
 * Date: 19.08.2017
 * Time: 04:04
 */

namespace application\forms;

use application\models\Items\Meta;
use yii\base\Model;

/**
 * Class MetatagsForm
 * @package application\forms
 */
class MetatagsForm extends Model
{
    public $title;
    public $description;
    public $keywords;

    /**
     * MetatagsForm constructor.
     *
     * @param Meta|null $meta
     * @param array|null $config
     */
    public function __construct(Meta $meta = null, array $config = null)
    {
        if ($meta) {
            $this->title = $meta->title;
            $this->description = $meta->description;
            $this->keywords = $meta->keywords;
        }
        parent::__construct($config);
    }

    /**
     * @return array
     */
    public function rules() : array
    {
        return [
            [['title'], 'string', 'max' => 255],
            [['description', 'keywords'], 'string'],
        ];
    }
}