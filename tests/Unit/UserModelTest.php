<?php

use App\Models\User;

test('user model has correct fillable attributes', function () {
    $user = new User();

    expect($user->getFillable())->toContain('name')
        ->toContain('email')
        ->toContain('password');
});

test('user model has correct hidden attributes', function () {
    $user = new User();

    expect($user->getHidden())->toContain('password')
        ->toContain('remember_token');
});

test('user model casts email_verified_at to datetime', function () {
    $user = new User();
    $casts = $user->getCasts();

    expect($casts)->toHaveKey('email_verified_at');
    expect($casts['email_verified_at'])->toBe('datetime');
});

test('user model casts password to hashed', function () {
    $user = new User();
    $casts = $user->getCasts();

    expect($casts)->toHaveKey('password');
    expect($casts['password'])->toBe('hashed');
});
