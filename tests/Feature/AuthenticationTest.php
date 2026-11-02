<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registered_user_is_assigned_staff_role(): void
    {
        $response = $this->post('/register', [
            'name' => 'Staff Baru',
            'email' => 'staff@example.test',
            'phone' => '08123456789',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertDatabaseHas('users', ['email' => 'staff@example.test', 'role' => 'staff']);
    }

    public function test_staff_cannot_open_user_management(): void
    {
        $staff = User::create(['name' => 'Staff Test', 'email' => 'staff@test.local', 'password' => 'Password123!', 'role' => 'staff']);
        $this->actingAs($staff)->get('/users')->assertForbidden();
    }
}
