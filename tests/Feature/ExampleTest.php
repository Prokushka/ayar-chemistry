<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     */
    use RefreshDatabase;

    public function test_the_application_returns_a_successful_response(): void
    {
        Product::factory()->count(4)->create();
        $response = $this->get('/');

        $response->assertStatus(200);
    }
}
