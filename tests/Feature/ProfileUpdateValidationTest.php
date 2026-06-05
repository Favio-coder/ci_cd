<?php

use App\Models\User;

test('profile update requires name', function () {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->patch('/profile', [
            'name' => '',
            'email' => 'valid@example.com',
        ]);

    $response->assertSessionHasErrors('name');
});

test('profile update requires valid email', function () {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->patch('/profile', [
            'name' => 'Test User',
            'email' => 'not-valid-email',
        ]);

    $response->assertSessionHasErrors('email');
});

test('profile update prevents using another users email', function () {
    $existingUser = User::factory()->create(['email' => 'taken@example.com']);
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->patch('/profile', [
            'name' => 'Test User',
            'email' => 'taken@example.com',
        ]);

    $response->assertSessionHasErrors('email');
});
