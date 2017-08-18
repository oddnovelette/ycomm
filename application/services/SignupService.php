<?php

namespace application\services;

use application\models\User;
use application\forms\SignupForm;
use application\repositories\UserRepository;
use yii\mail\MailerInterface;

class SignupService
{
    private $mailer;
    private $userRepository;

    public function __construct(MailerInterface $mailer, UserRepository $userRepository)
    {
        $this->mailer = $mailer;
        $this->userRepository = $userRepository;
    }

    public function signup(SignupForm $form) : void
    {
        $user = User::signup($form->username, $form->email, $form->password);
        $this->userRepository->save($user);

        $send = $this->mailer->compose(
            ['html' => 'auth/signup/confirm-html', 'text' => 'auth/signup/confirm-text'],
            ['user' => $user]
        )
            ->setTo($user->email)
            ->setSubject('Signup confirm for ' . \Yii::$app->name)
            ->send();

        if (!$send) throw new \RuntimeException('Email sending fault');
    }

    public function confirm(string $token) : void
    {
        if (empty($token)) throw new \DomainException('Empty confirm token');
        $user = $this->userRepository->getByEmailConfirmToken($token);
        $user->signupConfirmation();
        $this->userRepository->save($user);
    }

}