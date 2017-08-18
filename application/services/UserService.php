<?php
/**
 * Created by PhpStorm.
 * User: odd
 * Date: 18.08.2017
 * Time: 00:40
 */

namespace application\services;

use application\forms\Users\UserEditForm;
use application\models\User;
use application\repositories\UserRepository;
use application\forms\Users\UserCreateForm;

class UserService
{
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function create(UserCreateForm $form) : User
    {
        $user = User::manualCreate($form->username, $form->password, $form->email);
        $this->userRepository->save($user);
        return $user;
    }

    public function edit(int $id, UserEditForm $form) : void
    {
        $user = $this->userRepository->get($id);
        $user->edit($form->username, $form->email);
        $this->userRepository->save($user);
    }

}