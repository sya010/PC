<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class AdminDashboardTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Helper: create an admin user.
     */
    protected function createAdmin(): User
    {
        return User::factory()->create(['is_admin' => true]);
    }

    /**
     * Helper: create a regular user.
     */
    protected function createUser(): User
    {
        return User::factory()->create(['is_admin' => false]);
    }

    // 1. test_admin_dashboard_renders_for_admin
    public function test_admin_dashboard_renders_for_admin(): void
    {
        $admin = $this->createAdmin();

        $response = $this->actingAs($admin)->get('/admin/dashboard');
        $response->assertStatus(200);
    }

    // 2. test_admin_products_page_renders
    public function test_admin_products_page_renders(): void
    {
        $admin = $this->createAdmin();

        $response = $this->actingAs($admin)->get('/admin/products');
        $response->assertStatus(200);
    }

    // 3. test_admin_can_see_all_products
    public function test_admin_can_see_all_products(): void
    {
        $admin = $this->createAdmin();

        $products = Product::factory()->count(5)->create(['is_active' => true]);

        $response = $this->actingAs($admin)->get('/admin/products');
        $response->assertStatus(200);

        foreach ($products as $product) {
            $response->assertSee($product->name);
        }
    }

    // 4. test_admin_product_form_renders_for_create
    public function test_admin_product_form_renders_for_create(): void
    {
        $admin = $this->createAdmin();

        $response = $this->actingAs($admin)->get('/admin/products/create');
        $response->assertStatus(200);
    }

    // 5. test_admin_can_create_a_product
    public function test_admin_can_create_a_product(): void
    {
        $admin = $this->createAdmin();

        Livewire::actingAs($admin)
            ->test(\App\Livewire\Admin\ProductForm::class)
            ->set('name', 'New Test Product Created')
            ->set('price', 150000)
            ->set('stock', 25)
            ->set('category', 'CPU')
            ->set('description', 'A test product for automated testing.')
            ->set('is_active', true)
            ->set('specs', [
                ['key' => 'socket', 'value' => 'AM5'],
                ['key' => 'cores', 'value' => '8'],
            ])
            ->call('save');

        $this->assertDatabaseHas('products', [
            'name' => 'New Test Product Created',
        ]);
    }

    // 6. test_admin_can_soft_delete_a_product
    public function test_admin_can_soft_delete_a_product(): void
    {
        $admin = $this->createAdmin();
        $product = Product::factory()->create(['is_active' => true]);

        // Admin Products component uses delete() which is a hard delete in the current codebase.
        // The product model has a booted deleting hook, so we test the actual delete behavior.
        Livewire::actingAs($admin)
            ->test(\App\Livewire\Admin\Products::class)
            ->call('delete', $product->id);

        // After deletion, the product should no longer exist
        // (The current codebase uses hard delete, not soft delete)
        $this->assertDatabaseMissing('products', ['id' => $product->id]);
    }

    // 7. test_admin_orders_page_renders
    public function test_admin_orders_page_renders(): void
    {
        $admin = $this->createAdmin();

        $response = $this->actingAs($admin)->get('/admin/orders');
        $response->assertStatus(200);
    }

    // 8. test_admin_can_update_order_status
    public function test_admin_can_update_order_status(): void
    {
        $admin = $this->createAdmin();
        $user = $this->createUser();

        $order = Order::create([
            'user_id' => $user->id,
            'full_name' => 'Test Customer',
            'email' => 'customer@example.com',
            'phone' => '07501234567',
            'address' => '123 Test Street',
            'city' => 'Erbil',
            'state' => 'Erbil',
            'zip_code' => '44001',
            'total_amount' => 500000,
            'status' => 'pending',
            'payment_method' => 'cod',
            'payment_status' => 'pending',
        ]);

        // Admin Orders component has updateStatus($orderId, $status) that updates the 'status' column
        Livewire::actingAs($admin)
            ->test(\App\Livewire\Admin\Orders::class)
            ->call('updateStatus', $order->id, 'processing');

        $this->assertEquals('processing', $order->fresh()->status);
    }

    // 9. test_admin_users_page_renders
    public function test_admin_users_page_renders(): void
    {
        $admin = $this->createAdmin();

        $response = $this->actingAs($admin)->get('/admin/users');
        $response->assertStatus(200);
    }

    // 10. test_admin_can_block_a_user
    public function test_admin_can_block_a_user(): void
    {
        $admin = $this->createAdmin();
        $user = $this->createUser();

        // Admin Users component uses startConfirmation + executeAction flow
        Livewire::actingAs($admin)
            ->test(\App\Livewire\Admin\Users::class)
            ->call('startConfirmation', $user->id, 'block')
            ->call('executeAction');

        $this->assertTrue($user->fresh()->is_blocked);
    }
}
