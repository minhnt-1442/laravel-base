<?php

namespace Tests\Unit;

use App\User;
use Tests\TestCase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

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

        $this->assertTrue(Hash::check('123456', $user['password']));
    }
}
