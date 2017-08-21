<?php
/**
 * Created by PhpStorm.
 * User: odd
 * Date: 20.08.2017
 * Time: 16:37
 */

namespace application\models\Items;

use yii\db\ActiveRecord;

/**
 * Class Overwiev
 * @package application\models\Items
 */
class Overwiev extends ActiveRecord
{
    /**
     * @param $userId
     * @param int $vote
     * @param string $text
     * @return Overwiev
     */
    public static function create($userId, int $vote, string $text): self
    {
        $review = new static();
        $review->user_id = $userId;
        $review->vote = $vote;
        $review->text = $text;
        $review->created_at = time();
        $review->active = false;
        return $review;
    }

    /**
     * @param $vote
     * @param $text
     */
    public function edit($vote, $text) : void
    {
        $this->vote = $vote;
        $this->text = $text;
    }
    public function decline() : void
    {
        $this->active = false;
    }
    public function accept() : void
    {
        $this->active = true;
    }
    public function isDeclined() : bool
    {
        return $this->active === false;
    }
    public function isAccepted() : bool
    {
        return $this->active === true;
    }
    public function getRating() : bool
    {
        return $this->vote;
    }
    public function isIdEqualTo($id) : bool
    {
        return $this->id == $id;
    }
    public static function tableName() : string
    {
        return '{{%item_overviews}}';
    }
}