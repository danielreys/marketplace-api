<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function test_registers_a_user()
    {
        $userData = [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123',
        ];

        $response = $this->postJson('api/register', $userData);

        $response->assertStatus(ResponseAlias::HTTP_OK)
            ->assertJsonStructure(['token']);

        $this->assertDatabaseHas('users', [
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
    }

    /** @test */
    public function test_logs_in_a_user()
    {
        User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password123'),
        ]);

        $loginData = [
            'email' => 'test@example.com',
            'password' => 'password123',
        ];

        $response = $this->postJson('api/login', $loginData);

        $response->assertStatus(ResponseAlias::HTTP_OK)
            ->assertJsonStructure(['token']);
    }
}
