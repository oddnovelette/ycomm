<?php
/**
 * Created by PhpStorm.
 * User: odd
 * Date: 25.08.2017
 * Time: 06:49
 */

namespace backend\forms;

/**
 * Class PricingFormatter
 * @package backend\forms
 */
class PricingFormatter
{
    public static function format($price) : string
    {
        return number_format($price, 0, '.', ' ');
    }
}