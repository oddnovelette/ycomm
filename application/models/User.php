<?php
namespace application\models;

use lhs\Yii2SaveRelationsBehavior\SaveRelationsBehavior;
use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\{ActiveQuery, ActiveRecord};
use yii\helpers\ArrayHelper;
use yii\web\IdentityInterface;

/**
 * User model
 *
 * @property integer $id
 * @property string $username
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property string $auth_key
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $password write-only password
 */
class User extends ActiveRecord implements IdentityInterface
{
    const STATUS_AWAIT = 0;
    const STATUS_ACTIVE = 10;

    /**
     * @inheritdoc
     */
    public static function tableName() : string
    {
        return '{{%users}}';
    }

    /**
     * @param string $username
     * @param string $email
     * @param string $password
     * @return User
     */
    public static function signup(string $username, string $email, string $password) : self
    {
        $user = new self();
        $user->username = $username;
        $user->email = $email;
        $user->setPassword($password);
        $user->created_at = time();
        $user->status = self::STATUS_ACTIVE; // for consideration
        $user->newConfirmToken();
        $user->generateAuthKey();
        return $user;
    }

    /**
     * @param string $username
     * @param string $email
     * @param string $password
     * @return User
     */
    public static function manualCreate(string $username, string $email, string $password) : self
    {
        $user = new self();
        $user->username = $username;
        $user->email = $email;
        $user->password = $password;
        $user->created_at = time();
        $user->status = self::STATUS_ACTIVE;
        $user->generateAuthKey();
        return $user;
    }

    /**
     * @param string $username
     * @param string $email
     * @return void
     */
    public function edit(string $username, string $email) : void
    {
        $this->username = $username;
        $this->email = $email;
        $this->updated_at = time();
    }

    /**
     * @return void
     */
    public function signupConfirmation() : void
    {
        if (!$this->isAwait()) throw new \DomainException('User confirmed');
        $this->status = self::STATUS_ACTIVE;
        $this->clearResetToken();
    }

    /**
     * @param string $social
     * @param string $identity
     * @return User
     */
    public static function signupWithSocial(string $social, string $identity) : self
    {
        $user = new self();
        $user->created_at = time();
        $user->status = self::STATUS_ACTIVE;
        $user->generateAuthKey();
        $user->socials = [Social::create($social, $identity)];
        return $user;
    }

    /**
     * @return void
     */
    public function requestPasswordReset() : void
    {
        if (!empty($this->password_reset_token) && self::isPasswordResetTokenValid($this->password_reset_token)) {
            throw new \DomainException('Password reset is already sent');
        }
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     * @return void
     */
    private function setPassword(string $password) : void
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * @param string $password
     * @return void
     */
    public function resetPassword(string $password) : void
    {
        if (empty($this->password_reset_token)) {
            throw new \DomainException('Password reset not requested');
        }
        $this->setPassword($password);
        $this->clearResetToken();
    }

    /**
     * @return bool
     */
    public function isActive() : bool
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    public function isAwait() : bool
    {
        return $this->status === self::STATUS_AWAIT;
    }

    public function behaviors() : array
    {
        return [
            TimestampBehavior::className(),
            [
                'class' => SaveRelationsBehavior::className(),
                'relations' => ['socials'],
            ],
        ];
    }

    public function rules() : array
    {
        return [
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_AWAIT]],
        ];
    }

    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername(string $username)
    {
        return static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken(string $token)
    {
        if (!static::isPasswordResetTokenValid($token)) return null;

        return static::findOne([
            'password_reset_token' => $token,
            'status' => self::STATUS_ACTIVE,
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return bool
     */
    public static function isPasswordResetTokenValid(string $token) : bool
    {
        if (empty($token)) return false;
        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }

    /**
     * @inheritdoc
     */
    public function getId() : int
    {
        return $this->getPrimaryKey();
    }

    public function getAuthKey() : string
    {
        return $this->auth_key;
    }

    public function getSocials() : ActiveQuery
    {
        return $this->hasMany(Social::className(), ['user_id' => 'id']);
    }

    public function validateAuthKey($authKey) : string
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword(string $password) : bool
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates "remember me" auth key
     */
    private function generateAuthKey() : void
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    private function newConfirmToken() : void
    {
        $this->email_confirm_token = Yii::$app->security->generateRandomString();
    }

    private function clearResetToken() : void
    {
        $this->password_reset_token = null;
    }

    /*
     * Sets transactions for entire model
     */
    public function transactions() : array
    {
        return [self::SCENARIO_DEFAULT => self::OP_ALL];
    }

    public static function getNamedStatus() : array
    {
        return [
            User::STATUS_AWAIT => 'User waiting',
            User::STATUS_ACTIVE => 'Active user'
        ];
    }

    /**
     * @param $status
     * @return string
     */
    public static function getStatus($status) : string
    {
        return ArrayHelper::getValue(self::getNamedStatus(), $status);
    }
}
