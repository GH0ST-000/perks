<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('can register a new user', function (): void {
    $response = $this->postJson('/api/auth/register', [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $response->assertCreated();
    $response->assertJsonStructure([
        'message',
        'user' => [
            'id',
            'name',
            'email',
            'created_at',
            'updated_at',
        ],
    ]);

    $this->assertDatabaseHas('users', [
        'name' => 'Test User',
        'email' => 'test@example.com',
    ]);
});

it('can login with valid credentials', function (): void {
    $user = User::factory()->create([
        'email' => 'test@example.com',
        'password' => bcrypt('password'),
    ]);

    $response = $this->postJson('/api/auth/login', [
        'email' => 'test@example.com',
        'password' => 'password',
    ]);

    $response->assertOk();
    $response->assertJsonStructure([
        'access_token',
        'token_type',
        'expires_in',
    ]);
});

it('cannot login with invalid credentials', function (): void {
    $user = User::factory()->create([
        'email' => 'test@example.com',
        'password' => bcrypt('password'),
    ]);

    $response = $this->postJson('/api/auth/login', [
        'email' => 'test@example.com',
        'password' => 'wrong-password',
    ]);

    $response->assertUnauthorized();
    $response->assertJson([
        'error' => 'Unauthorized',
    ]);
});

it('can get authenticated user', function (): void {
    $user = User::factory()->create([
        'email' => 'test@example.com',
        'password' => bcrypt('password'),
    ]);

    $token = auth('api')->login($user);

    $response = $this->withHeaders([
        'Authorization' => 'Bearer '.$token,
    ])->getJson('/api/auth/me');

    $response->assertOk();
    $response->assertJson([
        'id' => $user->id,
        'name' => $user->name,
        'email' => $user->email,
    ]);
});

it('can refresh token', function (): void {
    $user = User::factory()->create([
        'email' => 'test@example.com',
        'password' => bcrypt('password'),
    ]);

    $token = auth('api')->login($user);

    $response = $this->withHeaders([
        'Authorization' => 'Bearer '.$token,
    ])->postJson('/api/auth/refresh');

    $response->assertOk();
    $response->assertJsonStructure([
        'access_token',
        'token_type',
        'expires_in',
    ]);
});

it('can logout', function (): void {
    $user = User::factory()->create([
        'email' => 'test@example.com',
        'password' => bcrypt('password'),
    ]);

    $token = auth('api')->login($user);

    $response = $this->withHeaders([
        'Authorization' => 'Bearer '.$token,
    ])->postJson('/api/auth/logout');

    $response->assertOk();
    $response->assertJson([
        'message' => 'Successfully logged out',
    ]);
});
