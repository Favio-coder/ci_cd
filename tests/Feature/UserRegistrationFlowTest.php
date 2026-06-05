<?php

use App\Models\User;

test('new user can register and is redirected to dashboard', function () {
    $response = $this->post('/register', [
        'name' => 'New User',
        'email' => 'newuser@example.com',
        'password' => 'SecurePass123!',
        'password_confirmation' => 'SecurePass123!',
    ]);

    $this->assertAuthenticated();
    $response->assertRedirect(route('dashboard', absolute: false));
});

test('registered user is persisted in database', function () {
    $this->post('/register', [
        'name' => 'Persisted User',
        'email' => 'persisted@example.com',
        'password' => 'SecurePass123!',
        'password_confirmation' => 'SecurePass123!',
    ]);

    $this->assertDatabaseHas('users', [
        'name' => 'Persisted User',
        'email' => 'persisted@example.com',
    ]);
});

test('registered user password is hashed in database', function () {
    $this->post('/register', [
        'name' => 'Hash Test User',
        'email' => 'hashtest@example.com',
        'password' => 'SecurePass123!',
        'password_confirmation' => 'SecurePass123!',
    ]);

    $user = User::where('email', 'hashtest@example.com')->first();

    expect($user->password)->not->toBe('SecurePass123!');
    expect(password_verify('SecurePass123!', $user->password))->toBeTrue();
});
