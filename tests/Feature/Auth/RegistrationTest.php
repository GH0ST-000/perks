<?php

test('registration screen is not available when public registration is disabled', function () {
    config(['perks.registration_enabled' => false]);

    $response = $this->get('/register');

    $response->assertNotFound();
});

test('registration screen can be rendered when enabled', function () {
    config(['perks.registration_enabled' => true]);

    $response = $this->get('/register');

    $response->assertOk();
});
