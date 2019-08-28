<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\User;

class UserTest extends TestCase
{
    use DatabaseTransactions;
    use WithFaker;
    /**
     * A test Encrypt Password.
     *
     * @return void
     */
    public function testEncryptPassword()
    {
        $user = factory(User::class)->create([
            'password' => $password = "123456",
        ]);

        $this->assertNotEquals($password, $user['password']);
        $this->assertNotEmpty($user['password']);
    }
}
