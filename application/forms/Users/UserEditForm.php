<?php
/**
 * Created by PhpStorm.
 * User: odd
 * Date: 18.08.2017
 * Time: 01:12
 */

namespace application\forms\Users;


use application\models\User;
use yii\base\Model;

class UserEditForm extends Model
{
    public $username;
    public $email;
    public $status;
    private $_user;

    public function __construct(User $user, array $config = null)
    {
        $this->username = $user->username;
        $this->email = $user->email;
        $this->status = $user->status;
        $this->_user = $user;
        parent::__construct($config);
    }

    public function rules() : array
    {
        return [
            [['username', 'email'], 'required'],
            ['email', 'email'],
            ['email', 'string', 'min' => 6, 'max' => 100],
            [['username', 'email'], 'unique', 'targetClass' => User::class,
                // validate except current model
                'filter' => ['<>', 'id', $this->_user->id]],
        ];
    }

}