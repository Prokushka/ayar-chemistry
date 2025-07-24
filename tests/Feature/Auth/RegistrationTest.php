<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_screen_can_be_rendered(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
    }

    public function test_new_users_can_register(): void
    {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'phone' => 89 . rand(100000000,999999999),
            'password' => 'Svenmider337228337',
            'password_confirmation' => 'Svenmider337228337',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('main', absolute: false));
    }
}
