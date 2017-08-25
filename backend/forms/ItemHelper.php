<?php
/**
 * Created by PhpStorm.
 * User: odd
 * Date: 25.08.2017
 * Time: 07:02
 */

namespace backend\forms;

use application\models\Items\Item;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/**
 * Class ItemHelper
 * @package backend\forms
 */
class ItemHelper
{
    public static function statusList() : array
    {
        return [
            Item::STATUS_DRAFT => 'Draft',
            Item::STATUS_ACTIVE => 'Active',
        ];
    }

    public static function statusName($status) : string
    {
        return ArrayHelper::getValue(self::statusList(), $status);
    }

    public static function statusLabel($status) : string
    {
        switch ($status) {

            case Item::STATUS_DRAFT:
                $class = 'label label-default';
                break;

            case Item::STATUS_ACTIVE:
                $class = 'label label-success';
                break;

            default:
                $class = 'label label-default';
        }
        return Html::tag('span', ArrayHelper::getValue(self::statusList(), $status), [
            'class' => $class,
        ]);
    }
}