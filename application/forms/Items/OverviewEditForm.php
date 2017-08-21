<?php
/**
 * Created by PhpStorm.
 * User: odd
 * Date: 20.08.2017
 * Time: 15:58
 */

namespace application\forms\Items;

use application\models\Items\Overwiev;
use yii\base\Model;

/**
 * Class OverviewEditForm
 * @package application\forms\Items
 */
class OverviewEditForm extends Model
{
    public $vote;
    public $text;

    /**
     * OverviewEditForm constructor.
     * @param Overwiev $overwiew
     * @param array|null $config
     */
    public function __construct(Overwiev $overwiew, array $config = null)
    {
        $this->vote = $overwiew->vote;
        $this->text = $overwiew->text;
        parent::__construct($config);
    }

    public function rules() : array
    {
        return [
            [['vote', 'text'], 'required'],
            [['vote'], 'in', 'range' => [1, 2, 3, 4, 5]],
            ['text', 'string'],
        ];
    }
}