<?php

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

test('profile update request validates name is required', function () {
    $rules = (new ProfileUpdateRequest())->rules();

    expect($rules)->toHaveKey('name');
    expect($rules['name'])->toContain('required');
});

test('profile update request validates email is required', function () {
    $rules = (new ProfileUpdateRequest())->rules();

    expect($rules)->toHaveKey('email');
    expect($rules['email'])->toContain('required');
});

test('profile update request validates email must be lowercase', function () {
    $rules = (new ProfileUpdateRequest())->rules();

    expect($rules['email'])->toContain('lowercase');
});

test('profile update request validates name max length', function () {
    $rules = (new ProfileUpdateRequest())->rules();

    expect($rules['name'])->toContain('max:255');
});
