<?php

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

test('registration screen can be rendered', function () {
    $response = $this->get(route('register'));

    $response->assertStatus(200);
});

test('new users can register', function () {
    Storage::fake('public');

    $response = $this->post(route('register.store'), [
        'name' => 'Alyana Kamille',
        'email' => 'yanaaamille@gmail.com',
        'password' => 'password',
        'password_confirmation' => 'password',

        'id_pictures' => [
            UploadedFile::fake()->image('id1.jpg'), // 👈 fake image added
        ],
    ]);

    $response->assertSessionHasNoErrors()
        ->assertRedirect(route('home', absolute: false));

    $this->assertAuthenticated();
});
