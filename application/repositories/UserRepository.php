<?php
namespace application\repositories;

use application\models\User;
use yii\web\NotFoundHttpException;

class UserRepository
{
    public function findByUsernameOrEmail($value) : ? User
    {
        return User::find()->andWhere(['or', ['username' => $value], ['email' => $value]])->one();
    }

    public function findBySocialIdentity($social, $identity) : ? User
    {
        return User::find()->joinWith('socials n')->andWhere(['n.social' => $social, 'n.identity' => $identity])->one();
    }

    public function getByEmailConfirmToken(string $token) : User
    {
        return $this->getBy(['email_confirm_token' => $token]);
    }

    public function getByEmail(string $email) : User
    {
        return $this->getBy(['email' => $email]);
    }

    public function existsByPasswordResetToken(string $token) : bool
    {
        return (bool) User::findByPasswordResetToken($token);
    }

    public function  getByPasswordResetToken(string $token) : User
    {
        return $this->getBy(['password_reset_token' => $token]);
    }
    public function save(User $user) : void
    {
        if (!$user->save()) throw new \RuntimeException('Saving fault');
    }

    private function getBy(array $checktype) : User
    {
        if (!$user = User::find()->andWhere($checktype)->limit(1)->one()) {
            throw new NotFoundHttpException('User not found');
        }
        return $user;
    }

}