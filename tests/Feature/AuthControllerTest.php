<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Http\Controllers\AuthController;
use Tests\TestCase;
use App\User;
use Illuminate\Foundation\Testing\WithFaker;

class UserTest extends TestCase
{
    use DatabaseTransactions;
    use WithFaker;
    /**
     * A test Signup Success.
     *
     * @return void
     */
    public function testSignupSuccess()
    {
        $response = $this->json('POST', route('api.signup'), [
            'name' => $this->faker->name(),
            'email' => $this->faker->email(),
            'password' => "123456",
            'password_confirmation' => "123456",
        ]);
        $response->assertStatus(201)->assertJson([
            'message' => 'Successfully created user!',
            'user' => [
                'name' => User::latest()->first()->name,
                'email' => User::latest()->first()->email,
                'updated_at' => User::latest()->first()->updated_at,
                'created_at' => User::latest()->first()->created_at,
                'id' => User::latest()->first()->id,
            ],
        ]);
    }

    /**
     * A test Signup Fail With Wrong Email.
     *
     * @return void
     */
    public function testSignupFailWithWrongEmail()
    {
        $response = $this->json('POST', route('api.signup'), [
            'name' => $this->faker->name(),
            'email' => $this->faker->name(),
            'password' => "123456",
            'password_confirmation' => "123456",
        ]);
        $response->assertStatus(400)->assertJson([
            'success' => false,
            'error' => [
                'code' => 622,
                'message' => "The email must be a valid email address.",
            ],
        ]);
    }

    /**
     * A test Signup Fail With Password Confirmation Not Match.
     *
     * @return void
     */
    public function testSignupFailWithPasswordConfirmationNotMatch()
    {
        $response = $this->json('POST', route('api.signup'), [
            'name' => $this->faker->name(),
            'email' => $this->faker->email(),
            'password' => "123456",
            'password_confirmation' => "1234567",
        ]);

        $response->assertStatus(400)->assertJson([
            'success' => false,
            'error' => [
                'code' => 622,
                'message' => "The password confirmation does not match.",
            ],
        ]);
    }

    /**
     * A test Login Success.
     *
     * @return void
     */
    public function testLoginSuccess()
    {
        $user = factory(User::class)->create([
            'password' => $password = "123456",
        ]);

        $response = $this->json('POST', route('api.login'), [
            'email' => $user->email,
            'password' => $password,
        ]);

        $response->assertJsonStructure([
          'access_token',
          'token_type',
          'expires_at',
        ]);
    }

    /**
     * A test Cannot Login With Incorrect Password.
     *
     * @return void
     */
    public function testCannotLoginWithIncorrectPassword()
    {
        $user = factory(User::class)->create([
            'password' => "123456",
        ]);

        $response = $this->json('POST', route('api.login'), [
            'email' => $user->email,
            'password' => 'invalid-password',
        ]);

        $response->assertStatus(401)->assertJson([
            "message" => "Unauthorized",
        ]);
    }

    /**
     * A test Cannot Login With Incorrect Email.
     *
     * @return void
     */
    public function testCannotLoginWithIncorrectEmail()
    {
        $user = factory(User::class)->create([
            'password' => "123456",
        ]);

        $response = $this->json('POST', route('api.login'), [
            'email' => $user->email . "x",
            'password' => '123456',
        ]);

        $response->assertStatus(401)->assertJson([
            "message" => "Unauthorized",
        ]);
    }

    /**
     * A test Logout Success.
     *
     * @return void
     */
    public function testLogoutSuccess()
    {
        $user = factory(User::class)->create([
            'password' => "123456",
        ]);

        $response = $this->withHeaders([
          'Authorization' => 'Bearer ' . $user->createToken('Personal Access Token')->accessToken,
        ])->json('GET', route('api.logout'));

        $response->assertStatus(200)->assertJson([
            "message" => "Successfully logged out",
        ]);
    }
}
