<?php

namespace application\forms\Users;

use application\models\User;
use yii\base\Model;

class UserCreateForm extends Model
{
    public $username;
    public $email;
    public $password;

    public function rules() : array
    {
        return [
            [['username', 'email'], 'unique'],
            [['username', 'email', 'password'], 'required'],
            ['email', 'email'],
            [['username'], 'string', 'min' => 2, 'max' => 100],
            [['email', 'password'], 'string', 'min' => 6, 'max' => 100],
            [['username', 'email'], 'targetClass' => User::class],
        ];
    }
}