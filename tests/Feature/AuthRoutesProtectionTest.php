<?php

use App\Models\User;

test('authenticated users are redirected from register page', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get('/register');

    $response->assertRedirect(route('dashboard', absolute: false));
});

test('authenticated users are redirected from forgot password page', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get('/forgot-password');

    $response->assertRedirect(route('dashboard', absolute: false));
});

test('register page can be rendered for guests', function () {
    $response = $this->get('/register');

    $response->assertStatus(200);
});
