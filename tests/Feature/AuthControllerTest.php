<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Http\Controllers\AuthController;
use Tests\TestCase;
use App\User;

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
            'email' => 'nguyen.thanh.minh@sun-asterisk.com',
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
     * @dataProvider providerTestSignupFail
     * @return void
     */
    public function testSignupFail($userData, $responseData)
    {
        $response = $this->json('POST', route('api.signup'), $userData);
        $response->assertStatus(400)->assertJson($responseData);
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
     * A test Login Fail.
     *
     * @dataProvider providerTestLoginFail
     * @return void
     */
    public function testLoginFail($dataUser, $dataLogin, $dataResponse)
    {
        $user = factory(User::class)->create($dataUser);

        $response = $this->json('POST', route('api.login'), $dataLogin);

        $response->assertStatus(400)->assertJson($dataResponse);
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
        ])->json('POST', route('api.logout'));

        $response->assertStatus(200)->assertJson([
            "message" => "Successfully logged out",
        ]);
    }

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
