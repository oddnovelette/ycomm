<?php
/**
 * Created by PhpStorm.
 * User: odd
 * Date: 19.08.2017
 * Time: 04:12
 */

namespace application\repositories;

use application\models\Items\Label;

/**
 * Class LabelRepository
 * @package application\repositories
 */
class LabelRepository
{
    /**
     * @param int $id
     * @return Label
     */
    public function get(int $id) : Label
    {
        if (!$label = Label::findOne($id)) throw new \DomainException('Label not found.');
        return $label;
    }

    /**
     * @param Label $label
     * @return void
     */
    public function save(Label $label) : void
    {
        if (!$label->save()) throw new \RuntimeException('Saving fault');
    }

    /**
     * @param Label $label
     * @return void
     */
    public function remove(Label $label) : void
    {
        if (!$label->delete()) throw new \RuntimeException('Deleting fault');
    }
}