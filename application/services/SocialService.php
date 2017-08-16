<?php
/**
 * Created by PhpStorm.
 * User: odd
 * Date: 14.08.2017
 * Time: 17:30
 */

namespace application\services;

use application\models\User;
use application\repositories\UserRepository;

class SocialService
{
    private $users;

    public function __construct(UserRepository $users)
    {
        $this->users = $users;
    }

    public function auth($social, $identity) : User
    {
        if ($user = $this->users->findBySocialIdentity($social, $identity)) {
            return $user;
        }
        $user = User::signupWithSocial($social, $identity);
        $this->users->save($user);
        return $user;
    }

}