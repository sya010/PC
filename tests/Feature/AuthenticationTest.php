<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    // 1. test_registration_page_renders
    public function test_registration_page_renders(): void
    {
        $response = $this->get('/register');
        $response->assertStatus(200);
    }

    // 2. test_user_can_register_with_valid_data
    public function test_user_can_register_with_valid_data(): void
    {
        $this->get('/register'); // Initialize session

        \Livewire\Livewire::test(\App\Livewire\Auth\Register::class)
            ->set('name', 'Test User')
            ->set('email', 'test@example.com')
            ->set('password', 'password123')
            ->set('password_confirmation', 'password123')
            ->set('terms', true)
            ->call('register');

        $this->assertDatabaseHas('users', ['email' => 'test@example.com']);
    }

    // 3. test_registration_fails_without_name
    public function test_registration_fails_without_name(): void
    {
        \Livewire\Livewire::test(\App\Livewire\Auth\Register::class)
            ->set('name', '')
            ->set('email', 'test@example.com')
            ->set('password', 'password123')
            ->set('password_confirmation', 'password123')
            ->set('terms', true)
            ->call('register')
            ->assertHasErrors(['name']);
    }

    // 4. test_registration_fails_with_duplicate_email
    public function test_registration_fails_with_duplicate_email(): void
    {
        User::factory()->create(['email' => 'existing@example.com']);

        \Livewire\Livewire::test(\App\Livewire\Auth\Register::class)
            ->set('name', 'Another User')
            ->set('email', 'existing@example.com')
            ->set('password', 'password123')
            ->set('password_confirmation', 'password123')
            ->set('terms', true)
            ->call('register')
            ->assertHasErrors(['email']);
    }

    // 5. test_registration_fails_with_short_password
    public function test_registration_fails_with_short_password(): void
    {
        \Livewire\Livewire::test(\App\Livewire\Auth\Register::class)
            ->set('name', 'Test User')
            ->set('email', 'test@example.com')
            ->set('password', 'short')
            ->set('password_confirmation', 'short')
            ->set('terms', true)
            ->call('register')
            ->assertHasErrors(['password']);
    }

    // 6. test_login_page_renders
    public function test_login_page_renders(): void
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
    }

    // 7. test_user_can_login_with_correct_credentials
    public function test_user_can_login_with_correct_credentials(): void
    {
        $user = User::factory()->create([
            'password' => bcrypt('password123'),
        ]);

        \Livewire\Livewire::test(\App\Livewire\Auth\Login::class)
            ->set('email', $user->email)
            ->set('password', 'password123')
            ->call('login');

        $this->assertAuthenticated();
    }

    // 8. test_login_fails_with_wrong_password
    public function test_login_fails_with_wrong_password(): void
    {
        $user = User::factory()->create([
            'password' => bcrypt('password123'),
        ]);

        \Livewire\Livewire::test(\App\Livewire\Auth\Login::class)
            ->set('email', $user->email)
            ->set('password', 'wrongpassword')
            ->call('login');

        $this->assertGuest();
    }

    // 9. test_login_fails_for_blocked_user
    public function test_login_fails_for_blocked_user(): void
    {
        $user = User::factory()->create([
            'password' => bcrypt('password123'),
            'is_blocked' => true,
        ]);

        \Livewire\Livewire::test(\App\Livewire\Auth\Login::class)
            ->set('email', $user->email)
            ->set('password', 'password123')
            ->call('login')
            ->assertHasErrors(['email']);

        $this->assertGuest();
    }

    // 10. test_authenticated_user_can_logout
    public function test_authenticated_user_can_logout(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user);
        $this->assertAuthenticated();

        $response = $this->post('/logout');
        $this->assertGuest();
    }

    // 11. test_guest_is_redirected_from_protected_routes
    public function test_guest_is_redirected_from_protected_routes(): void
    {
        $response = $this->get('/my-orders');
        $response->assertRedirect('/login');
    }

    // 12. test_admin_middleware_blocks_non_admin
    public function test_admin_middleware_blocks_non_admin(): void
    {
        $user = User::factory()->create([
            'is_admin' => false,
        ]);

        $response = $this->actingAs($user)->get('/admin/dashboard');
        $response->assertStatus(403);
    }
}
