<?php
/**
 * Created by PhpStorm.
 * User: odd
 * Date: 13.08.2017
 * Time: 02:48
 */

namespace application\services;

use Yii;
use application\repositories\UserRepository;

use application\forms\{
    ResetPasswordForm,
    PasswordResetRequestForm
};
use yii\mail\MailerInterface;

class PasswordResetService
{
    private $mailer;
    private $userRepository;

    public function __construct(UserRepository $userRepository, MailerInterface $mailer)
    {
        $this->mailer = $mailer;
        $this->userRepository = $userRepository;
    }

    public function request(PasswordResetRequestForm $form) : void
    {
        $user = $this->userRepository->getByEmail($form->email);
        if (!$user) throw new \DomainException('User not found');
        if (!$user->isActive()) throw new \DomainException('User is not active.');

        $user->requestPasswordReset();
        $this->userRepository->save($user);

        $send = $this->mailer->compose(
            ['html' => 'auth/reset/confirm-html', 'text' => 'auth/reset/confirm-text'],
            ['user' => $user]
        )
        ->setTo($user->email)
        ->setSubject('Password reset for ' . Yii::$app->name)
        ->send();

        if (!$send) throw new \RuntimeException('Sending fault');
    }

    public function validateToken($token) : void
    {
        if (empty($token) || !is_string($token)) {
            throw new \DomainException('Reset token can`t be blank');
        }
        if (!$this->userRepository->existsByPasswordResetToken($token)) {
            throw new \DomainException('Wrong password reset token');
        }
    }

    public function reset(string $token, ResetPasswordForm $form) : void
    {
        $user = $this->userRepository->getByPasswordResetToken($token);
        $user->resetPassword($form->password);
        $this->userRepository->save($user);
    }
}