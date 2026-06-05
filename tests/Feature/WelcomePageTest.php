<?php

use App\Models\User;

test('welcome page returns successful response', function () {
    $response = $this->get('/');

    $response->assertStatus(200);
});

test('welcome page contains login link for guests', function () {
    $response = $this->get('/');

    $response->assertStatus(200);
    $response->assertSee('Log in');
});
