<?php
/**
 * Created by PhpStorm.
 * User: odd
 * Date: 13.08.2017
 * Time: 16:00
 */

namespace common\tests\unit\models;


use common\models\User;
use Helper\Unit;

class RequestSignupTest extends Unit
{
    public function testSuccess()
    {
        $user = User::signup(
            $username = 'username',
            $email = 'email@site.com',
            $password = 'password'
        );
        $this->assertEquals($username, $user->username);
        $this->assertEquals($email, $user->email);
        $this->assertNotEmpty($user->password_hash);
        $this->assertNotEquals($password, $user->password_hash);
        $this->assertNotEmpty($user->created_at);
        $this->assertNotEmpty($user->auth_key);
        $this->assertNotEmpty($user->email_confirm_token);
        $this->assertTrue($user->isAwait());
        $this->assertFalse($user->isActive());
    }

}