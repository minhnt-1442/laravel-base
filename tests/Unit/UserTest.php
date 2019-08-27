<?php
namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\User;

class UserTest extends TestCase
{
    /**
     * A test User model.
     *
     * @return void
     */
    public function testSetPasswordReturnsTrueWhenPasswordSuccessfullySet()
    {
        $details = [];

        $user = new User($details);
    
        $password = 'fubar';
    
        $user->password = $password;
    
        $this->assertNotEquals($password, $user['password']);
        $this->assertNotEmpty($user['password']);
    }
}