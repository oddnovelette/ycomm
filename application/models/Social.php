<?php
/**
 * Created by PhpStorm.
 * User: odd
 * Date: 14.08.2017
 * Time: 01:41
 */

namespace application\models;
use yii\db\ActiveRecord;
use yii\web\BadRequestHttpException;

class Social extends ActiveRecord
{
    public static function create(string $social, string $identity) : self
    {
        if (empty($social) || empty($identity)) {
            throw new BadRequestHttpException('Identities could not be empty');
        }
        $social_identity = new static();
        $social_identity->social = $social;
        $social_identity->identity = $identity;
        return $social_identity;
    }

    public static function tableName()
    {
        return '{{%user_socials}}';
    }
}