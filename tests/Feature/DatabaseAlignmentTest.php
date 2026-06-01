<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Component;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DatabaseAlignmentTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test User is_admin and role sync.
     */
    public function test_user_role_and_is_admin_are_synchronized(): void
    {
        $user = User::factory()->create([
            'is_admin' => true,
        ]);

        $this->assertEquals('admin', $user->role);

        $user->role = 'user';
        $user->save();

        $this->assertFalse($user->is_admin);

        $user2 = User::factory()->create([
            'role' => 'admin',
        ]);

        $this->assertTrue($user2->is_admin);
    }

    /**
     * Test Product fields syncing and pc_components auto-population.
     */
    public function test_product_fields_sync_and_pc_component_creation(): void
    {
        $product = Product::factory()->create([
            'name' => 'Intel Core i9-14900K',
            'category' => 'CPU',
            'stock' => 15,
            'image' => 'products/cpu-i9.png',
            'specs' => [
                'socket' => 'LGA1700',
                'tdp' => '125W',
                'cores' => 24,
                'needs' => [
                    'supported_memory_type' => 'DDR5'
                ]
            ]
        ]);

        // Verify synced columns
        $this->assertEquals(15, $product->stock_quantity);
        $this->assertEquals('products/cpu-i9.png', $product->image_path);
        $this->assertArrayHasKey('socket', $product->specifications);

        // Verify pc_components row was created
        $component = Component::where('product_id', $product->id)->first();
        $this->assertNotNull($component);
        $this->assertEquals('cpu', $component->component_type);
        $this->assertEquals('LGA1700', $component->socket_type);
        $this->assertEquals('DDR5', $component->ram_type);

        // Update product stock and verify sync
        $product->stock_quantity = 5;
        $product->save();
        $this->assertEquals(5, $product->stock);
    }

    /**
     * Test OrderItem unit_price and subtotal calculation.
     */
    public function test_order_item_unit_price_and_subtotal_calculation(): void
    {
        $user = User::factory()->create();
        $order = Order::create([
            'user_id' => $user->id,
            'full_name' => 'John Doe',
            'email' => 'john@example.com',
            'phone' => '12345678',
            'address' => 'Baghdad',
            'city' => 'Baghdad',
            'state' => 'Baghdad',
            'zip_code' => '10011',
            'total_amount' => 300,
        ]);

        $item = OrderItem::create([
            'order_id' => $order->id,
            'product_name' => 'Test GPU',
            'quantity' => 2,
            'price' => 150.00,
        ]);

        $this->assertEquals(150.00, $item->unit_price);
        $this->assertEquals(300.00, $item->subtotal);

        // Verify order syncing of shipping information
        $this->assertEquals('John Doe', $order->shipping_name);
        $this->assertEquals('12345678', $order->shipping_phone);
        $this->assertStringContainsString('Baghdad', $order->shipping_address);
    }
}
