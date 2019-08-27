<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Http\Controllers\AuthController;
use App\User;
use Illuminate\Foundation\Testing\WithFaker;

class UserTest extends TestCase
{
    use DatabaseTransactions;
    use WithFaker;
    /**
     * A test User model.
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

    public function testLoginSuccess()
    {
        $user = factory(User::class)->create([
            'name' => $this->faker->name(),
            'email' => 'abcdef@test.com',
            'password' => "123456",
        ]);
        // dd($user );

        $response = $this->json('POST', route('api.login'), [
            'email' => 'abcdef@test.com',
            'password' => "123456",
        ]);

        dd($response);
        $response->assertStatus(200)->assertJsonEqual([
          'access_token',
          'token_type',
          'expires_at',
        ]);
    }
}