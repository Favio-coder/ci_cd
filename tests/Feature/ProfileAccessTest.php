<?php

use App\Models\User;

test('guests cannot access profile edit page', function () {
    $response = $this->get('/profile');

    $response->assertRedirect('/login');
});

test('guests cannot update profile', function () {
    $response = $this->patch('/profile', [
        'name' => 'Hacker',
        'email' => 'hacker@example.com',
    ]);

    $response->assertRedirect('/login');
});

test('guests cannot delete profile', function () {
    $response = $this->delete('/profile', [
        'password' => 'password',
    ]);

    $response->assertRedirect('/login');
});
