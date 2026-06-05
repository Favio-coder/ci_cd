<?php

use App\Http\Requests\Auth\LoginRequest;

test('login request validates email is required', function () {
    $rules = (new LoginRequest())->rules();

    expect($rules)->toHaveKey('email');
    expect($rules['email'])->toContain('required');
});

test('login request validates password is required', function () {
    $rules = (new LoginRequest())->rules();

    expect($rules)->toHaveKey('password');
    expect($rules['password'])->toContain('required');
});

test('login request validates email must be email type', function () {
    $rules = (new LoginRequest())->rules();

    expect($rules['email'])->toContain('email');
});

test('login request authorizes all users', function () {
    $request = new LoginRequest();

    expect($request->authorize())->toBeTrue();
});
