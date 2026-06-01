<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductCatalogTest extends TestCase
{
    use RefreshDatabase;

    // 1. test_shop_page_renders
    public function test_shop_page_renders(): void
    {
        $response = $this->get('/shop');
        $response->assertStatus(200);
    }

    // 2. test_products_are_displayed_on_shop_page
    public function test_products_are_displayed_on_shop_page(): void
    {
        $products = Product::factory()->count(3)->create([
            'is_active' => true,
            'stock' => 10,
        ]);

        $response = $this->get('/shop');
        $response->assertStatus(200);

        foreach ($products as $product) {
            $response->assertSee($product->name);
        }
    }

    // 3. test_inactive_products_are_not_displayed
    public function test_inactive_products_are_not_displayed(): void
    {
        $active = Product::factory()->create([
            'is_active' => true,
            'name' => 'Active Product Visible',
            'stock' => 10,
        ]);
        $inactive = Product::factory()->create([
            'is_active' => false,
            'name' => 'Inactive Product Hidden',
            'stock' => 10,
        ]);

        $response = $this->get('/shop');
        $response->assertStatus(200);
        $response->assertSee('Active Product Visible');
        $response->assertDontSee('Inactive Product Hidden');
    }

    // 4. test_product_detail_page_renders
    public function test_product_detail_page_renders(): void
    {
        $product = Product::factory()->create(['is_active' => true]);

        $response = $this->get('/product/' . $product->id);
        $response->assertStatus(200);
    }

    // 5. test_product_detail_shows_correct_name
    public function test_product_detail_shows_correct_name(): void
    {
        $product = Product::factory()->create([
            'is_active' => true,
            'name' => 'Unique Test CPU XYZ',
        ]);

        $response = $this->get('/product/' . $product->id);
        $response->assertStatus(200);
        $response->assertSee('Unique Test CPU XYZ');
    }

    // 6. test_product_search_returns_matching_results
    public function test_product_search_returns_matching_results(): void
    {
        Product::factory()->create([
            'is_active' => true,
            'name' => 'AMD Ryzen 9 Special',
        ]);
        Product::factory()->create([
            'is_active' => true,
            'name' => 'Intel Core i9 Regular',
        ]);

        // The Shop component uses the 'search' query string parameter
        $response = $this->get('/shop?search=AMD+Ryzen');
        $response->assertStatus(200);
        $response->assertSee('AMD Ryzen 9 Special');
        $response->assertDontSee('Intel Core i9 Regular');
    }

    // 7. test_product_category_filter_works
    public function test_product_category_filter_works(): void
    {
        Product::factory()->create([
            'is_active' => true,
            'name' => 'GPU Product RTX',
            'category' => 'GPU',
        ]);
        Product::factory()->create([
            'is_active' => true,
            'name' => 'RAM Product DDR5',
            'category' => 'RAM',
        ]);

        // The Shop component uses the 'category' query string parameter (lowercase key mapped to DB value)
        $response = $this->get('/shop?category=gpu');
        $response->assertStatus(200);
        $response->assertSee('GPU Product RTX');
        $response->assertDontSee('RAM Product DDR5');
    }

    // 8. test_product_detail_page_returns_404_for_missing_product
    public function test_product_detail_page_returns_404_for_missing_product(): void
    {
        $response = $this->get('/product/99999');
        $response->assertStatus(404);
    }
}
