<?php

use App\Models\User;

test('user model uses HasFactory trait', function () {
    $user = new User();

    expect(method_exists($user, 'factory'))->toBeTrue();
});

test('user model uses Notifiable trait', function () {
    $user = new User();

    expect(method_exists($user, 'notify'))->toBeTrue();
});

test('user model extends Authenticatable', function () {
    $user = new User();

    expect($user)->toBeInstanceOf(\Illuminate\Foundation\Auth\User::class);
});

test('user model has remember_token in hidden', function () {
    $user = new User();
    $hidden = $user->getHidden();

    expect($hidden)->toContain('remember_token');
});
