<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\Concerns\InteractsWithDatabase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Tests\Url;

class AuthTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    protected $register  = '/api/register';
    protected $login_url = '/api/login';
    protected $details   = '/api/user';

    public function test_validator()
    {
        $data = $this->getUserData();
        unset($data['email']);

        $response = $this->postJson(Url::register, $data);
        $response->assertStatus(400);
        $response->assertSeeText("The email field is required");

        $data['email'] = $this->faker->email;
        unset($data['username']);

        $response = $this->postJson(Url::register, $data);
        $response->assertStatus(400);
        $response->assertSeeText("The username field is required");

        $data['username'] = $this->faker->unique()->userName();
        unset($data['name']);

        $response = $this->postJson(Url::register, $data);
        $response->assertStatus(400);
        $response->assertSeeText("The name field is required");
    }

    public function test_duplicate_email()
    {
        $email = $this->faker->email;

        $data = $this->getUserData(null, $email);

        $response = $this->postJson(Url::register, $data);
        $response->assertStatus(200);

        $data = $this->getUserData(null, $email);

        $response = $this->postJson(Url::register, $data);
        $response->assertStatus(400);
        $response->assertSeeText('The email has already been taken');
    }

    public function test_register()
    {
        $data = $this->getUserData();

        $response = $this->postJson(Url::register, $data);
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'access_token',
            'token_type'
        ]);
    }

    public function test_login()
    {
        $wrong_password = "password";
        $right_password = "Password101";

        $data = $this->getUserData($right_password);

        $response = $this->postJson(Url::register, $data);
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'id',
            'access_token',
            'token_type'
        ]);

        $login_data = [
            'email'        => $data['email'],
            'password'     => $wrong_password,
        ];

        $response = $this->postJson(Url::login, $login_data);
        $response->assertStatus(401);
        $response->assertSeeText("Invalid login");

        $login_data['password'] = $right_password;

        $response = $this->postJson(Url::login, $login_data);
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'id',
            'access_token',
            'token_type'
        ]);
    }

    public function test_user_details()
    {
        $data = $this->getUserData();

        $response = $this->postJson(Url::register, $data);
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'access_token',
            'token_type'
        ]);

        $access_token = $response->json()['access_token'];

        $response = $this->withHeader('Authorization', 'Bearer ' . $access_token)
            ->get($this->details);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'id',
            'name',
            'username'
        ]);
    }
}
