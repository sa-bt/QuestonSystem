<?php

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Register Test
     */
    public function test_register_should_be_validated()
    {
        $response = $this->postJson(route('register'));
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function test_new_user_can_register()
    {
        $response = $this->postJson(route('register'), [
            "name"     => "Seyed Ahmad Bakhshian",
            "email"    => "sa.bt@chmail.ir",
            "password" => "123456",
        ]);
        $response->assertStatus(Response::HTTP_CREATED);
    }

    /**
     * Login Test
     */
    public function test_login_should_be_validated()
    {
        $response = $this->postJson(route('login'));
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function test_user_can_login_with_true_credentials()
    {
        $user     = User::factory()->create();
        $response = $this->postJson(route('login'), [
            "email"    => $user->email,
            "password" => "password",
        ]);
        $response->assertStatus(Response::HTTP_OK);
    }

    /**
     * Logout Test
     */
    public function test_logged_in_user_can_logout()
    {
        $user     = User::factory()->create();
        $response = $this->postJson(route('logout'));
        $response->assertStatus(Response::HTTP_OK);
    }

    /**
     * Test Logged User Get Information
     */

    public function test_show_user_info_if_logged_in()
    {
        $user     = User::factory()->create();
        $response = $this->getJson(route('user'));
        $response->assertStatus(Response::HTTP_OK);
    }
}
