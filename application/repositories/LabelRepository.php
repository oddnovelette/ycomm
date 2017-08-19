<?php
/**
 * Created by PhpStorm.
 * User: odd
 * Date: 19.08.2017
 * Time: 04:12
 */

namespace application\repositories;

use application\models\Items\Label;

class LabelRepository
{
    public function get(int $id) : Label
    {
        if (!$label = Label::findOne($id)) throw new \DomainException('Brand is not found.');
        return $label;
    }

    public function save(Label $label) : void
    {
        if (!$label->save()) throw new \RuntimeException('Saving error.');
    }

    public function remove(Label $label) : void
    {
        if (!$label->delete()) throw new \RuntimeException('Removing error.');
    }
}