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

/**
 * Class SocialService
 * @package application\services
 */
class SocialService
{
    private $users;

    /**
     * SocialService constructor.
     * @param UserRepository $users
     */
    public function __construct(UserRepository $users)
    {
        $this->users = $users;
    }

    /**
     * @param $social
     * @param $identity
     * @return User
     */
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