<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UserTest extends TestCase
{
    use DatabaseTransactions;
    use WithFaker;
    /**
     * A test signup success.
     */
    public function testSignupSuccess()
    {
        $password = $this->faker->password();
        $name = $this->faker->name();
        $email = $this->faker->safeEmail;
        $response = $this->json('POST', route('api.signup'), [
            'name' => $name,
            'email' => $email,
            'password' => $password,
            'password_confirmation' => $password,
        ]);
        $user = User::latest()->first();
        $response->assertStatus(201)->assertJson([
            'message' => 'Successfully created user!',
            'user' => [
                'name' => $name,
                'email' => $email,
                'updated_at' => $user->updated_at,
                'created_at' => $user->created_at,
                'id' => $user->id,
            ],
        ]);
    }

    /**
     * A test signup fail with wrong email.
     *
     * @dataProvider providerTestSignupFail
     * @return void
     */
    public function testSignupFail($userData, $responseData)
    {
        $response = $this->json('POST', route('api.signup'), $userData);
        $response->assertStatus(400)->assertJson($responseData);
    }

    /**
     * A test login success.
     *
     * @return void
     */
    public function testLoginSuccess()
    {
        $email = $this->faker->safeEmail;
        $password = $this->faker->password;
        $user = factory(User::class)->create([
            'email' => $email,
            'password' => $password,
        ]);

        $response = $this->json('POST', route('api.login'), [
            'email' => $email,
            'password' => $password,
        ]);

        $response->assertJsonStructure([
          'access_token',
          'token_type',
          'expires_at',
        ]);
    }

    /**
     * A test login fail.
     * @dataProvider providerTestLoginFail
     * @return void
     */
    public function testLoginFail($dataUser, $dataLogin, $dataResponse)
    {
        factory(User::class)->create($dataUser);

        $response = $this->json('POST', route('api.login'), $dataLogin);

        $response->assertStatus(400)->assertJson($dataResponse);
    }

    /**
     * A test logout success.
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
        ])->json('POST', route('api.logout'));

        $response->assertStatus(200)->assertJson([
            "message" => "Successfully logged out",
        ]);
    }

    /**
     * A provider test signup fail.
     *
     * @return array
     */
    public function providerTestSignupFail()
    {
        return [
            [
                [
                    'name' => "Minh",
                    'email' => 'nguyen.thanh.minh',
                    'password' => "123456",
                    'password_confirmation' => "123456",
                ],
                [
                    'success' => false,
                    'error' => [
                        'code' => 622,
                        'message' => "The email must be a valid email address.",
                    ],
                ]
            ],
            [
                [
                    'name' => "Minh",
                    'email' => 'nguyen.thanh.minh@sun-asterisk.com',
                    'password' => "123456",
                    'password_confirmation' => "1234567",
                ],
                [
                    'success' => false,
                    'error' => [
                        'code' => 622,
                        'message' => "The password confirmation does not match.",
                    ],
                ]
            ]
        ];
    }

    /**
     * A provider test login fail.
     *
     * @return array
     */
    public function providerTestLoginFail()
    {
        return [
            [
                [
                    'name' => "Minh",
                    'email' => 'nguyen.thanh.minh1@sun-asterisk.com',
                    'password' => "123456",
                ],
                [
                    'email' => 'nguyen.thanh.minh@sun.com',
                    'password' => "123456",
                ],
                [
                    "success" => false,
                    "error" => [
                        "code" => 601,
                        "message" => "Unauthorized, please check your credentials."
                    ]
                ]
            ],
            [
                [
                    'name' => "Minh",
                    'email' => 'nguyen.thanh.minh1@sun-asterisk.com',
                    'password' => "123456",
                ],
                [
                    'email' => 'nguyen.thanh.minh1@sun-asterisk.com',
                    'password' => "1234567",
                ],
                [
                    "success" => false,
                    "error" => [
                        "code" => 601,
                        "message" => "Unauthorized, please check your credentials."
                    ]
                ]
            ]
        ];
    }
}
