<?php
namespace application\services;

use application\models\User;
use application\forms\LoginForm;
use application\repositories\UserRepository;

class AuthService
{
    private $users;

    public function __construct(UserRepository $users)
    {
        $this->users = $users;
    }

    public function auth(LoginForm $form) : User
    {
        $user = $this->users->findByUserNameOrEmail($form->username);
        if (!$user || !$user->isActive() || !$user->validatePassword($form->password)) {
            throw new \DomainException('Wrong login or password');
        }
        return $user;
    }
}