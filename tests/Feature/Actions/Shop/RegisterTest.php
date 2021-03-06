<?php

namespace Tests\Feature\Actions\Shop;

use App\Models\User;
use Tests\Psr4\TestCases\JsonHttpTestCase;

class RegisterTest extends JsonHttpTestCase
{
    /** @test */
    public function can_register()
    {
        // given
        $email = 'abc123@example.com';
        $password = 'abc123';
        $username = 'janek';
        $forename = 'Jan';
        $surname = 'Nowak';
        $_SESSION['asid'] = 1;

        // when
        $response = $this->post('/', [
            'action'          => 'register',
            'username'        => $username,
            'password'        => $password,
            'password_repeat' => $password,
            'email'           => $email,
            'email_repeat'    => $email,
            'forename'        => $forename,
            'surname'         => $surname,
            'as_id'           => 1,
            'as_answer'       => 'e',
        ]);

        // then
        $this->assertEquals(200, $response->getStatusCode());
        $user = new User(0, $username, $password);
        $this->assertNotNull($user->getUid());
        $this->assertEquals($email, $user->getEmail(false));
        $this->assertEquals($forename, $user->getForename(false));
        $this->assertEquals($surname, $user->getSurname(false));
        $this->assertNotNull($user->getRegdate());
    }
}
