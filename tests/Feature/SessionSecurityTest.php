<?php

use App\Models\User;

test('successful login creates authenticated session', function () {
    $user = User::factory()->create();

    $response = $this->post('/login', [
        'email' => $user->email,
        'password' => 'password',
    ]);

    $this->assertAuthenticated();
    $response->assertRedirect(route('dashboard', absolute: false));
});

test('logout invalidates session and redirects to home', function () {
    $user = User::factory()->create();

    $this->actingAs($user);
    $this->assertAuthenticated();

    $response = $this->post('/logout');

    $this->assertGuest();
    $response->assertRedirect('/');
});

test('users cannot access dashboard after logout', function () {
    $user = User::factory()->create();

    $this->actingAs($user);
    $this->post('/logout');

    $response = $this->get('/dashboard');
    $response->assertRedirect('/login');
});
