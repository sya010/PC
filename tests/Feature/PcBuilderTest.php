<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use App\Livewire\PcBuilder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class PcBuilderTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Helper: create a CPU product with specific socket.
     */
    protected function createCpu(string $socket = 'AM5', float $price = 300000): Product
    {
        return Product::factory()->create([
            'name' => 'Test CPU ' . $socket,
            'category' => 'CPU',
            'is_active' => true,
            'stock' => 10,
            'price' => $price,
            'specs' => [
                'facts' => ['socket' => $socket, 'cores' => 8, 'threads' => 16, 'tdp' => 65, 'boost_clock' => 5.0, 'brand' => 'AMD', 'model' => 'Test CPU'],
                'needs' => ['socket' => $socket, 'min_psu_wattage' => 195, 'supported_memory_type' => 'DDR5'],
                'provides' => ['performance_tier' => 80, 'threads' => 16],
                'limits' => ['max_memory_speed' => 6400, 'supported_chipsets' => ['X670E', 'B650']],
                'meta' => ['generation' => 'Ryzen 7000', 'architecture' => 'Zen 4', 'longevity_score' => 75, 'recommended_cooler_tdp' => 65],
                'socket' => $socket,
                'tdp' => 65,
                'cores' => 8,
            ],
        ]);
    }

    /**
     * Helper: create a Motherboard product with specific socket.
     */
    protected function createMotherboard(string $socket = 'AM5', float $price = 200000): Product
    {
        return Product::factory()->create([
            'name' => 'Test Motherboard ' . $socket,
            'category' => 'Motherboard',
            'is_active' => true,
            'stock' => 10,
            'price' => $price,
            'specs' => [
                'facts' => ['socket' => $socket, 'chipset' => 'B650', 'form_factor' => 'ATX', 'memory_type' => 'DDR5', 'max_memory_speed' => 6400, 'memory_slots' => 4, 'vrm_power_delivery' => 120, 'pcie_version' => 5.0, 'brand' => 'ASUS', 'model' => 'Test Board'],
                'needs' => ['cpu_socket' => $socket, 'ram_type' => 'DDR5'],
                'provides' => ['supports_socket' => $socket, 'max_memory_speed' => 6400, 'pcie_lanes' => 24, 'vrm_power_delivery' => 120],
                'limits' => ['max_ram_capacity' => 128, 'supported_chipsets' => ['B650'], 'max_gpu_length' => 330],
                'meta' => ['generation' => 'Ryzen 7000', 'upgrade_path' => 'DDR5 platform', 'performance_tier' => 75],
                'socket' => $socket,
                'chipset' => 'B650',
                'form_factor' => 'ATX',
                'memory_type' => 'DDR5',
                'max_memory_speed' => '6400MHz',
            ],
        ]);
    }

    /**
     * Helper: create a GPU product.
     */
    protected function createGpu(float $price = 500000): Product
    {
        return Product::factory()->create([
            'name' => 'Test GPU RTX',
            'category' => 'GPU',
            'is_active' => true,
            'stock' => 10,
            'price' => $price,
            'specs' => [
                'facts' => ['brand' => 'NVIDIA', 'model' => 'Test GPU', 'memory_gb' => 12, 'length_mm' => 267, 'tdp' => 220, 'pcie_version' => 4.0, 'slot_width' => 2],
                'needs' => ['psu_min_wattage' => 620, 'case_max_length' => 267, 'cpu_performance_tier_min' => 48],
                'provides' => ['performance_tier' => 78, 'vram' => 12],
                'limits' => ['max_length' => 267, 'slot_width' => 2],
                'meta' => ['generation' => 'Ada Lovelace', 'architecture' => 'NVIDIA', 'longevity_score' => 83],
                'tdp' => 220,
                'length' => 267,
                'vram' => '12GB',
            ],
        ]);
    }

    /**
     * Helper: build component data array matching PcBuilder's expected format.
     */
    protected function componentData(Product $product): array
    {
        return [
            'id' => $product->id,
            'name' => $product->name,
            'price' => $product->price,
            'image' => (string) $product->image_url,
            'specs' => $product->specs,
            'category' => $product->category,
        ];
    }

    // 1. test_pc_builder_page_renders
    public function test_pc_builder_page_renders(): void
    {
        $response = $this->get('/build-pc');
        $response->assertStatus(200);
    }

    // 2. test_pc_builder_initial_total_is_zero
    public function test_pc_builder_initial_total_is_zero(): void
    {
        Livewire::test(PcBuilder::class)
            ->assertSet('totalPrice', 0.0);
    }

    // 3. test_selecting_cpu_updates_total_price
    public function test_selecting_cpu_updates_total_price(): void
    {
        $cpu = $this->createCpu('AM5', 450000);

        // Simulate selecting a CPU via session (how ComponentSelector stores it)
        $this->withSession(['pc_build' => ['cpu' => $this->componentData($cpu)]]);

        Livewire::test(PcBuilder::class)
            ->assertSet('totalPrice', 450000.0);
    }

    // 4. test_selecting_motherboard_updates_total_price
    public function test_selecting_motherboard_updates_total_price(): void
    {
        $mobo = $this->createMotherboard('AM5', 200000);

        $this->withSession(['pc_build' => ['motherboard' => $this->componentData($mobo)]]);

        Livewire::test(PcBuilder::class)
            ->assertSet('totalPrice', 200000.0);
    }

    // 5. test_selecting_multiple_components_sums_total
    public function test_selecting_multiple_components_sums_total(): void
    {
        $cpu = $this->createCpu('AM5', 300000);
        $gpu = $this->createGpu(500000);

        $this->withSession(['pc_build' => [
            'cpu' => $this->componentData($cpu),
            'gpu' => $this->componentData($gpu),
        ]]);

        Livewire::test(PcBuilder::class)
            ->assertSet('totalPrice', 800000.0);
    }

    // 6. test_removing_component_reduces_total
    public function test_removing_component_reduces_total(): void
    {
        $cpu = $this->createCpu('AM5', 300000);

        $this->withSession(['pc_build' => ['cpu' => $this->componentData($cpu)]]);

        Livewire::test(PcBuilder::class)
            ->assertSet('totalPrice', 300000.0)
            ->call('removeComponent', 'cpu')
            ->assertSet('totalPrice', 0.0);
    }

    // 7. test_incompatible_cpu_motherboard_sets_error_status
    public function test_incompatible_cpu_motherboard_sets_error_status(): void
    {
        $cpu = $this->createCpu('LGA1700', 300000);
        $mobo = $this->createMotherboard('AM5', 200000);

        $this->withSession(['pc_build' => [
            'cpu' => $this->componentData($cpu),
            'motherboard' => $this->componentData($mobo),
        ]]);

        Livewire::test(PcBuilder::class)
            ->assertSet('compatibilityStatus', 'incompatible');
    }

    // 8. test_compatible_cpu_motherboard_passes
    public function test_compatible_cpu_motherboard_passes(): void
    {
        $cpu = $this->createCpu('LGA1700', 300000);
        // Create motherboard with matching LGA1700 socket
        $mobo = Product::factory()->create([
            'name' => 'Test Motherboard LGA1700',
            'category' => 'Motherboard',
            'is_active' => true,
            'stock' => 10,
            'price' => 200000,
            'specs' => [
                'facts' => ['socket' => 'LGA1700', 'chipset' => 'Z790', 'form_factor' => 'ATX', 'memory_type' => 'DDR5', 'max_memory_speed' => 6400, 'memory_slots' => 4, 'vrm_power_delivery' => 150, 'pcie_version' => 5.0, 'brand' => 'ASUS', 'model' => 'Test LGA1700 Board'],
                'needs' => ['cpu_socket' => 'LGA1700', 'ram_type' => 'DDR5'],
                'provides' => ['supports_socket' => 'LGA1700', 'max_memory_speed' => 6400, 'pcie_lanes' => 24, 'vrm_power_delivery' => 150],
                'limits' => ['max_ram_capacity' => 128, 'supported_chipsets' => ['Z790'], 'max_gpu_length' => 330],
                'meta' => ['generation' => 'Intel 12th-14th Gen', 'upgrade_path' => 'DDR5 platform', 'performance_tier' => 90],
                'socket' => 'LGA1700',
                'chipset' => 'Z790',
                'form_factor' => 'ATX',
                'memory_type' => 'DDR5',
                'max_memory_speed' => '6400MHz',
            ],
        ]);

        $this->withSession(['pc_build' => [
            'cpu' => $this->componentData($cpu),
            'motherboard' => $this->componentData($mobo),
        ]]);

        $component = Livewire::test(PcBuilder::class);

        // With compatible socket, all hard constraints should pass
        $report = $component->get('compatibilityReport');

        // Status should NOT be 'incompatible' — it should be 'compatible' or 'warning'
        $this->assertNotEquals('incompatible', $component->get('compatibilityStatus'));
    }

    // 9. test_reset_build_clears_all_selections
    public function test_reset_build_clears_all_selections(): void
    {
        $cpu = $this->createCpu('AM5', 300000);

        $this->withSession(['pc_build' => ['cpu' => $this->componentData($cpu)]]);

        Livewire::test(PcBuilder::class)
            ->set('resetTarget', 'all')
            ->call('executeReset')
            ->assertSet('selectedComponents.cpu', null);
    }

    // 10. test_compatibility_score_is_100_with_one_component
    public function test_compatibility_score_is_100_with_one_component(): void
    {
        $cpu = $this->createCpu('AM5', 300000);

        $this->withSession(['pc_build' => ['cpu' => $this->componentData($cpu)]]);

        Livewire::test(PcBuilder::class)
            ->assertSet('compatibilityScore', 100);
    }

    // 11. test_pc_builder_handles_nonexistent_product_gracefully
    public function test_pc_builder_handles_nonexistent_product_gracefully(): void
    {
        // Simulate a build session with a product ID that doesn't exist in DB
        // PcBuilder loads from session directly so it won't crash - it's just data
        $this->withSession(['pc_build' => [
            'cpu' => [
                'id' => 99999,
                'name' => 'Ghost CPU',
                'price' => 0,
                'image' => '',
                'specs' => [],
                'category' => 'CPU',
            ],
        ]]);

        // Should not throw an exception
        $component = Livewire::test(PcBuilder::class);
        $component->assertStatus(200);
    }

    // 12. test_add_build_to_cart_requires_at_least_one_component
    public function test_add_build_to_cart_requires_at_least_one_component(): void
    {
        $user = User::factory()->create();

        // Empty build — isValidBuild() returns false
        Livewire::actingAs($user)
            ->test(PcBuilder::class)
            ->call('addBuildToCart');

        // Cart should remain empty since no components selected
        $this->assertEquals([], session()->get('cart', []));
    }

    // 13. test_add_build_to_cart_adds_all_selected_items
    public function test_add_build_to_cart_adds_all_selected_items(): void
    {
        $user = User::factory()->create();
        $cpu = $this->createCpu('AM5', 300000);
        $gpu = $this->createGpu(500000);

        $this->withSession(['pc_build' => [
            'cpu' => $this->componentData($cpu),
            'gpu' => $this->componentData($gpu),
        ]]);

        Livewire::actingAs($user)
            ->test(PcBuilder::class)
            ->call('addBuildToCart');

        $cart = session()->get('cart', []);
        $this->assertArrayHasKey($cpu->id, $cart);
        $this->assertArrayHasKey($gpu->id, $cart);
    }

    // 14. test_pc_builder_shows_all_core_categories
    public function test_pc_builder_shows_all_core_categories(): void
    {
        $response = $this->get('/build-pc');
        $response->assertStatus(200);

        // The PC builder view uses translated labels from messages.pc_builder.*
        // Check for the translated English labels that appear in the rendered view
        $response->assertSee(__('messages.pc_builder.processor'), false);
        $response->assertSee(__('messages.pc_builder.motherboard'), false);
        $response->assertSee(__('messages.pc_builder.graphics_card'), false);
        $response->assertSee(__('messages.pc_builder.memory'), false);
        $response->assertSee(__('messages.pc_builder.storage'), false);
        $response->assertSee(__('messages.pc_builder.power_supply'), false);
        $response->assertSee(__('messages.pc_builder.case'), false);
    }

    // 15. test_component_selector_page_renders
    public function test_component_selector_page_renders(): void
    {
        $response = $this->get('/build-pc/select/cpu');
        $response->assertStatus(200);
    }
}
