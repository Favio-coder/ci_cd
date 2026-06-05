<?php

use App\Models\User;

test('forgot password page can be rendered', function () {
    $response = $this->get('/forgot-password');

    $response->assertStatus(200);
});

test('password reset link requires valid email format', function () {
    $response = $this->post('/forgot-password', [
        'email' => 'not-an-email',
    ]);

    $response->assertSessionHasErrors('email');
});

test('password reset link requires email field', function () {
    $response = $this->post('/forgot-password', [
        'email' => '',
    ]);

    $response->assertSessionHasErrors('email');
});
