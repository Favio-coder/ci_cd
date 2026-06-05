<?php

test('phpunit xml configures testing environment', function () {
    $phpunitXml = simplexml_load_file(__DIR__ . '/../../phpunit.xml');
    $envVars = [];

    foreach ($phpunitXml->php->env as $env) {
        $envVars[(string) $env['name']] = (string) $env['value'];
    }

    expect($envVars['APP_ENV'])->toBe('testing');
});

test('phpunit xml configures sqlite in memory database', function () {
    $phpunitXml = simplexml_load_file(__DIR__ . '/../../phpunit.xml');
    $envVars = [];

    foreach ($phpunitXml->php->env as $env) {
        $envVars[(string) $env['name']] = (string) $env['value'];
    }

    expect($envVars['DB_CONNECTION'])->toBe('sqlite');
    expect($envVars['DB_DATABASE'])->toBe(':memory:');
});

test('phpunit xml configures reduced bcrypt rounds', function () {
    $phpunitXml = simplexml_load_file(__DIR__ . '/../../phpunit.xml');
    $envVars = [];

    foreach ($phpunitXml->php->env as $env) {
        $envVars[(string) $env['name']] = (string) $env['value'];
    }

    expect((int) $envVars['BCRYPT_ROUNDS'])->toBeLessThanOrEqual(4);
});

test('phpunit xml configures array mail driver for testing', function () {
    $phpunitXml = simplexml_load_file(__DIR__ . '/../../phpunit.xml');
    $envVars = [];

    foreach ($phpunitXml->php->env as $env) {
        $envVars[(string) $env['name']] = (string) $env['value'];
    }

    expect($envVars['MAIL_MAILER'])->toBe('array');
});
