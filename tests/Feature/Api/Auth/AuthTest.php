<?php

namespace Tests\Feature\Api\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_login_and_receive_token(): void
    {
        $user = User::factory()->create();

        $response = $this->postJson('/api/auth/login', [
            "email" => $user->email,
            "password" => "password",
        ]);

        $response->assertOk();
        $response->assertJsonStructure(["token", "user"]);
    }

    public function test_user_cannot_login_with_incorrect_credentials(): void
    {
        $user = User::factory()->create();

        $response = $this->postJson('/api/auth/login', [
            "email" => $user->email,
            "password" => "wrong-password",
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(["email"]);
    }

    public function test_user_can_register_and_receive_token(): void
    {
        $response = $this->postJson('/api/auth/register',
            [
                "email" => "brunopw25@gmail.com",
                "password" => "password",
                "password_confirmation" => "password",
                "name" => "brunopw25"
            ]);

        $response->assertCreated();
        $response->assertJsonStructure(["token", "user"]);
        $this->assertDatabaseHas("users", ["email" => "brunopw25@gmail.com"]);
    }

    public function test_user_canot_register_with_invalid_data(): void
    {
        $response = $this->postJson('/api/auth/register',
            [
                "email" => "",
                "password" => "123",
                "password_confirmation" => "password",
                "name" => ""
            ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(["email", "password", "name"]);
    }


    public function test_user_can_logout_and_token_is_revoked(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);
        $response = $this->postJson('/api/auth/logout');

        $response->assertNoContent();

        // outra forma de fazer
//        $user = User::factory()->create();
//        $token = $user->createToken("laravel_api_token")->plainTextToken;
//        $response = $this->withHeader("Authorization", "Bearer ". $token)->postJson("/api/auth/logout");
//        $response->assertNoContent();
//        $this->app["auth"]->forgetGuards(); // Limpar o cache interno do guard
//        $protected = $this->withHeader("Authorization", "Bearer ". $token)->getJson("/api/user");
//        $protected->assertStatus(401);
    }

    public function test_guest_cannot_access_user_endpoint()
    {
        $response = $this->getJson('/api/user');
        $response->assertStatus(401);
    }

    public function test_authenticated_user_can_access_user_endpoint()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);
        $response = $this->getJson('/api/user');
        $response->assertOk();
        $response->assertJson(["id" => $user->id, "email" => $user->email]);
    }
}
