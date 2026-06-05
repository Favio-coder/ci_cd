<?php

use App\Models\User;

test('user model can be instantiated', function () {
    $user = new User();

    expect($user)->toBeInstanceOf(User::class);
});

test('user model table name is users', function () {
    $user = new User();

    expect($user->getTable())->toBe('users');
});

test('user model primary key is id', function () {
    $user = new User();

    expect($user->getKeyName())->toBe('id');
});

test('user model primary key type is int', function () {
    $user = new User();

    expect($user->getKeyType())->toBe('int');
});
