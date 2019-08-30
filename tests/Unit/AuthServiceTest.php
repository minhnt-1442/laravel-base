<?php

namespace Tests\Unit;

use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Services\AuthService;
use App\Http\Requests\SignUpRequest;
use App\Http\Requests\LoginRequest;
use Illuminate\Foundation\Testing\WithFaker;

class AuthServiceTest extends TestCase
{
    use DatabaseTransactions;
    use WithFaker;

    /**
    * @var AuthService
    */
    protected $authService;

    /**
    * {@inheritdoc}
    */
    public function setUp(): void
    {
        parent::setUp();
        $this->authService = new AuthService();
    }
  

    /**
     * A test create user
     *
     * @return void
     */
    public function testCreateUser()
    {
        $email = $this->faker->safeEmail;
        $name = $this->faker->name;
        $password = $this->faker->password;
        $request = new SignUpRequest();

        $request->replace([
          'name' => $name,
          'email' => $email,
          'password' => $password
        ]);
      
        $response = $this->authService->createUser($request);

        $this->assertInstanceOf(User::class, $response);
        $this->assertEquals($name, $response->name);
        $this->assertEquals($email, $response->email);
    }

    /**
     * A test handle login
     *
     * @return void
     */
    public function testHandleLogin() {
        $email = $this->faker->safeEmail;
        $password = $this->faker->password;
        $user = factory(User::class)->create([
            'email' => $email,
            'password' => $password,
        ]);

        $request = new LoginRequest();

        $request->replace([
          'email' => $email,
          'password' => $password,
        ]);
      
        $response = $this->authService->handleLogin($request);

        $this->assertIsArray($response);
        $this->assertArrayHasKey('access_token', $response);
        $this->assertArrayHasKey('token_type', $response);
        $this->assertArrayHasKey('expires_at', $response);
    }
}
