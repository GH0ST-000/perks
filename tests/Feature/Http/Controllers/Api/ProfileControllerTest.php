<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

uses(RefreshDatabase::class);

it('can update user profile', function (): void {
    $user = User::factory()->create([
        'name' => 'Original Name',
        'email' => 'original@example.com',
    ]);

    $token = auth('api')->login($user);

    $response = $this->withHeaders([
        'Authorization' => 'Bearer '.$token,
    ])->putJson('/api/profile/update', [
        'name' => 'Updated Name',
        'email' => 'updated@example.com',
    ]);

    $response->assertOk();
    $response->assertJson([
        'message' => 'Profile updated successfully',
    ]);

    $this->assertDatabaseHas('users', [
        'id' => $user->id,
        'name' => 'Updated Name',
        'email' => 'updated@example.com',
    ]);
});

it('can update user password', function (): void {
    $user = User::factory()->create([
        'password' => Hash::make('password'),
    ]);

    $token = auth('api')->login($user);

    $response = $this->withHeaders([
        'Authorization' => 'Bearer '.$token,
    ])->putJson('/api/profile/password', [
        'current_password' => 'password',
        'password' => 'new_password',
        'password_confirmation' => 'new_password',
    ]);

    $response->assertOk();
    $response->assertJson([
        'message' => 'Password updated successfully',
    ]);

    // Verify the password was updated by attempting to login with the new password
    $this->assertTrue(Hash::check('new_password', User::find($user->id)->password));
});

it('can update user profile photo', function (): void {
    $user = User::factory()->create([
        'profile_photo' => null,
    ]);

    $token = auth('api')->login($user);

    $response = $this->withHeaders([
        'Authorization' => 'Bearer '.$token,
    ])->putJson('/api/profile/photo', [
        'profile_photo' => 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNk+A8AAQUBAScY42YAAAAASUVORK5CYII=',
    ]);

    $response->assertOk();
    $response->assertJson([
        'message' => 'Profile photo updated successfully',
    ]);

    $this->assertDatabaseHas('users', [
        'id' => $user->id,
        'profile_photo' => 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNk+A8AAQUBAScY42YAAAAASUVORK5CYII=',
    ]);
});

it('cannot update profile without authentication', function (): void {
    $response = $this->putJson('/api/profile/update', [
        'name' => 'Updated Name',
        'email' => 'updated@example.com',
    ]);

    $response->assertUnauthorized();
});

it('cannot update password without authentication', function (): void {
    $response = $this->putJson('/api/profile/password', [
        'current_password' => 'password',
        'password' => 'new_password',
        'password_confirmation' => 'new_password',
    ]);

    $response->assertUnauthorized();
});

it('cannot update profile photo without authentication', function (): void {
    $response = $this->putJson('/api/profile/photo', [
        'profile_photo' => 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNk+A8AAQUBAScY42YAAAAASUVORK5CYII=',
    ]);

    $response->assertUnauthorized();
});
