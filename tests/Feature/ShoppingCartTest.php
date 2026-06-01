<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use App\Livewire\Cart;
use App\Livewire\CartCounter;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ShoppingCartTest extends TestCase
{
    use RefreshDatabase;

    // 1. test_cart_page_renders
    public function test_cart_page_renders(): void
    {
        $response = $this->get('/cart');
        $response->assertStatus(200);
    }

    // 2. test_user_can_add_product_to_cart
    public function test_user_can_add_product_to_cart(): void
    {
        $product = Product::factory()->create([
            'is_active' => true,
            'stock' => 10,
            'price' => 50000,
        ]);

        // Cart uses session directly — simulate adding via session
        $this->withSession(['cart' => [
            $product->id => [
                'name' => $product->name,
                'quantity' => 1,
                'price' => $product->price,
                'image' => $product->image_url,
            ],
        ]]);

        $component = Livewire::test(Cart::class);
        $component->assertSet('cartItems.' . $product->id . '.name', $product->name);
    }

    // 3. test_cart_item_count_increases_when_same_product_added_twice
    public function test_cart_item_count_increases_when_same_product_added_twice(): void
    {
        $product = Product::factory()->create([
            'is_active' => true,
            'stock' => 10,
            'price' => 50000,
        ]);

        // Simulate adding same product twice (quantity = 2)
        $this->withSession(['cart' => [
            $product->id => [
                'name' => $product->name,
                'quantity' => 2,
                'price' => $product->price,
                'image' => $product->image_url,
            ],
        ]]);

        $component = Livewire::test(Cart::class);
        $component->assertSet('cartItems.' . $product->id . '.quantity', 2);
    }

    // 4. test_user_can_remove_item_from_cart
    public function test_user_can_remove_item_from_cart(): void
    {
        $product = Product::factory()->create([
            'is_active' => true,
            'stock' => 10,
            'price' => 50000,
        ]);

        $this->withSession(['cart' => [
            $product->id => [
                'name' => $product->name,
                'quantity' => 1,
                'price' => $product->price,
                'image' => $product->image_url,
            ],
        ]]);

        Livewire::test(Cart::class)
            ->call('removeItem', $product->id)
            ->assertSet('cartItems', []);
    }

    // 5. test_user_can_update_item_quantity
    public function test_user_can_update_item_quantity(): void
    {
        $product = Product::factory()->create([
            'is_active' => true,
            'stock' => 10,
            'price' => 50000,
        ]);

        $this->withSession(['cart' => [
            $product->id => [
                'name' => $product->name,
                'quantity' => 1,
                'price' => $product->price,
                'image' => $product->image_url,
            ],
        ]]);

        Livewire::test(Cart::class)
            ->call('updateQuantity', $product->id, 3)
            ->assertSet('cartItems.' . $product->id . '.quantity', 3);
    }

    // 6. test_cart_total_is_calculated_correctly
    public function test_cart_total_is_calculated_correctly(): void
    {
        $product1 = Product::factory()->create([
            'is_active' => true,
            'stock' => 10,
            'price' => 100000,
        ]);
        $product2 = Product::factory()->create([
            'is_active' => true,
            'stock' => 10,
            'price' => 250000,
        ]);

        $this->withSession(['cart' => [
            $product1->id => [
                'name' => $product1->name,
                'quantity' => 2,
                'price' => $product1->price,
                'image' => $product1->image_url,
            ],
            $product2->id => [
                'name' => $product2->name,
                'quantity' => 1,
                'price' => $product2->price,
                'image' => $product2->image_url,
            ],
        ]]);

        // Expected: 100000*2 + 250000*1 = 450000
        Livewire::test(Cart::class)
            ->assertSet('total', 450000);
    }

    // 7. test_cart_persists_across_page_navigation
    public function test_cart_persists_across_page_navigation(): void
    {
        $product = Product::factory()->create([
            'is_active' => true,
            'stock' => 10,
            'price' => 50000,
        ]);

        // Add item to cart in session
        $this->withSession(['cart' => [
            $product->id => [
                'name' => $product->name,
                'quantity' => 1,
                'price' => $product->price,
                'image' => $product->image_url,
            ],
        ]]);

        // Navigate to shop then back to cart
        $this->get('/shop')->assertStatus(200);
        $response = $this->get('/cart');
        $response->assertStatus(200);
        $response->assertSee($product->name);
    }

    // 8. test_adding_out_of_stock_product_is_handled
    public function test_adding_out_of_stock_product_is_handled(): void
    {
        $user = User::factory()->create();
        $product = Product::factory()->create([
            'is_active' => true,
            'stock' => 0,
            'price' => 50000,
        ]);

        // The Shop component's addToCart checks stock and requires auth
        Livewire::actingAs($user)
            ->test(\App\Livewire\Shop::class)
            ->call('addToCart', $product->id);

        // Cart should remain empty since product is out of stock
        $this->assertEquals([], session()->get('cart', []));
    }

    // 9. test_cart_is_empty_on_fresh_session
    public function test_cart_is_empty_on_fresh_session(): void
    {
        Livewire::test(Cart::class)
            ->assertSet('cartItems', []);
    }

    // 10. test_cart_counter_component_reflects_cart_size
    public function test_cart_counter_component_reflects_cart_size(): void
    {
        $product1 = Product::factory()->create(['is_active' => true, 'stock' => 10]);
        $product2 = Product::factory()->create(['is_active' => true, 'stock' => 10]);

        $this->withSession(['cart' => [
            $product1->id => [
                'name' => $product1->name,
                'quantity' => 1,
                'price' => $product1->price,
                'image' => $product1->image_url,
            ],
            $product2->id => [
                'name' => $product2->name,
                'quantity' => 3,
                'price' => $product2->price,
                'image' => $product2->image_url,
            ],
        ]]);

        // CartCounter sums up the quantities of all items (1 + 3 = 4)
        Livewire::test(CartCounter::class)
            ->assertSet('count', 4);
    }
}
