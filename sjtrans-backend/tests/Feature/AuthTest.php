<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_user_can_login_using_username(): void
    {
        $response = $this->postJson('/api/v1/auth/login', [
            'login' => 'superadmin',
            'password' => 'password123',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure(['token', 'user' => ['id', 'name', 'username', 'email', 'role']])
            ->assertJsonPath('user.username', 'superadmin')
            ->assertJsonPath('user.role.name', 'super_admin');
    }

    public function test_user_can_login_using_email(): void
    {
        $response = $this->postJson('/api/v1/auth/login', [
            'login' => 'admin@sjtrans.co.id',
            'password' => 'password123',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure(['token', 'user'])
            ->assertJsonPath('user.email', 'admin@sjtrans.co.id');
    }

    public function test_customer_can_register_with_phone_number(): void
    {
        $response = $this->postJson('/api/v1/auth/register', [
            'name' => 'Ahmad Fauzi',
            'username' => 'ahmadfauzi',
            'email' => 'ahmad@example.com',
            'phone_number' => '081298765432',
            'password' => 'secret123',
            'password_confirmation' => 'secret123',
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure(['token', 'user'])
            ->assertJsonPath('user.username', 'ahmadfauzi')
            ->assertJsonPath('user.role.name', 'customer');

        $this->assertDatabaseHas('users', [
            'username' => 'ahmadfauzi',
            'email' => 'ahmad@example.com',
            'phone_number' => '081298765432',
        ]);
    }
}
