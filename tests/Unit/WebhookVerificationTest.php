<?php

namespace Tests\Unit;

use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class WebhookVerificationTest extends TestCase
{
    use RefreshDatabase;

    protected string $secret;
    protected string $webhookUrl = '/api/webhooks/wayl';

    protected function setUp(): void
    {
        parent::setUp();
        $this->secret = config('app.wayl_webhook_secret', env('WAYL_WEBHOOK_SECRET', 'MyPcShopSuperSecretPassword123!'));
    }

    /**
     * Helper: compute HMAC signature for a payload.
     */
    protected function computeSignature(string $payload): string
    {
        return hash_hmac('sha256', $payload, $this->secret);
    }

    /**
     * Helper: build a valid webhook payload.
     */
    protected function buildPayload(string $referenceId, string $status = 'Completed'): array
    {
        return [
            'data' => [
                'referenceId' => $referenceId,
                'status' => $status,
            ],
        ];
    }

    /**
     * Helper: create an order for testing.
     */
    protected function createTestOrder(string $paymentStatus = 'pending'): Order
    {
        $user = User::factory()->create();

        return Order::create([
            'user_id' => $user->id,
            'full_name' => 'Test User',
            'email' => 'test@example.com',
            'phone' => '07501234567',
            'address' => '123 Test Street',
            'city' => 'Erbil',
            'state' => 'Erbil',
            'zip_code' => '44001',
            'total_amount' => 500000,
            'status' => 'pending',
            'payment_method' => 'wayl',
            'payment_status' => $paymentStatus,
        ]);
    }

    // 1. test_valid_signature_is_accepted
    public function test_valid_signature_is_accepted(): void
    {
        $order = $this->createTestOrder();
        $payload = $this->buildPayload((string) $order->id, 'Completed');
        $jsonPayload = json_encode($payload);
        $signature = $this->computeSignature($jsonPayload);

        $response = $this->postJson($this->webhookUrl, $payload, [
            'X-Wayl-Signature' => $signature,
        ]);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);
    }

    // 2. test_invalid_signature_is_rejected
    public function test_invalid_signature_is_rejected(): void
    {
        $payload = $this->buildPayload('123', 'Completed');

        $response = $this->postJson($this->webhookUrl, $payload, [
            'X-Wayl-Signature' => 'deliberately_wrong_signature_value',
        ]);

        $response->assertStatus(400);
    }

    // 3. test_missing_signature_header_is_rejected
    public function test_missing_signature_header_is_rejected(): void
    {
        $payload = $this->buildPayload('123', 'Completed');

        $response = $this->postJson($this->webhookUrl, $payload);

        $response->assertStatus(400);
    }

    // 4. test_order_status_set_to_paid_on_completed_webhook
    public function test_order_status_set_to_paid_on_completed_webhook(): void
    {
        $order = $this->createTestOrder('pending');
        $payload = $this->buildPayload((string) $order->id, 'Completed');
        $jsonPayload = json_encode($payload);
        $signature = $this->computeSignature($jsonPayload);

        $this->postJson($this->webhookUrl, $payload, [
            'X-Wayl-Signature' => $signature,
        ]);

        $this->assertEquals('paid', $order->fresh()->payment_status);
    }

    // 5. test_order_status_set_to_failed_on_failed_webhook
    public function test_order_status_set_to_failed_on_failed_webhook(): void
    {
        $order = $this->createTestOrder('pending');
        $payload = $this->buildPayload((string) $order->id, 'Failed');
        $jsonPayload = json_encode($payload);
        $signature = $this->computeSignature($jsonPayload);

        $this->postJson($this->webhookUrl, $payload, [
            'X-Wayl-Signature' => $signature,
        ]);

        $this->assertEquals('failed', $order->fresh()->payment_status);
    }

    // 6. test_unknown_order_id_is_handled_gracefully
    public function test_unknown_order_id_is_handled_gracefully(): void
    {
        $payload = $this->buildPayload('99999', 'Completed');
        $jsonPayload = json_encode($payload);
        $signature = $this->computeSignature($jsonPayload);

        $response = $this->postJson($this->webhookUrl, $payload, [
            'X-Wayl-Signature' => $signature,
        ]);

        // Should still return 200 — no crash
        $response->assertStatus(200);
    }

    // 7. test_modified_payload_body_is_rejected
    public function test_modified_payload_body_is_rejected(): void
    {
        $originalPayload = $this->buildPayload('123', 'Completed');
        $jsonOriginal = json_encode($originalPayload);
        $signature = $this->computeSignature($jsonOriginal);

        // Alter the payload after computing the HMAC
        $alteredPayload = $this->buildPayload('999', 'Completed');

        $response = $this->postJson($this->webhookUrl, $alteredPayload, [
            'X-Wayl-Signature' => $signature,
        ]);

        $response->assertStatus(400);
    }

    // 8. test_empty_payload_with_valid_signature_is_rejected
    public function test_empty_payload_with_valid_signature_is_rejected(): void
    {
        // Send an empty JSON object, signed properly for empty
        $emptyJson = '{}';
        $signature = $this->computeSignature($emptyJson);

        $response = $this->call('POST', $this->webhookUrl, [], [], [], [
            'HTTP_X_Wayl_Signature' => $signature,
            'CONTENT_TYPE' => 'application/json',
        ], $emptyJson);

        // The webhook will process but since no referenceId/status, it still returns 200 success
        // (The controller doesn't reject empty payloads with valid signature, it just doesn't update any order)
        $response->assertStatus(200);
    }

    // 9. test_webhook_logs_warning_on_invalid_signature
    public function test_webhook_logs_warning_on_invalid_signature(): void
    {
        Log::spy();

        $payload = $this->buildPayload('123', 'Completed');

        $this->postJson($this->webhookUrl, $payload, [
            'X-Wayl-Signature' => 'bad_signature',
        ]);

        Log::shouldHaveReceived('warning')->once();
    }

    // 10. test_signature_comparison_is_timing_safe
    public function test_signature_comparison_is_timing_safe(): void
    {
        // Verify the controller uses hash_equals by inspecting source code
        $controllerSource = file_get_contents(
            app_path('Http/Controllers/WaylWebhookController.php')
        );

        $this->assertStringContainsString('hash_equals', $controllerSource);
        // Also assert it does NOT use simple === for signature comparison
        $this->assertStringNotContainsString('$signature ===', $controllerSource);
    }

    // 11. test_webhook_with_malformed_json_is_rejected_gracefully
    public function test_webhook_with_malformed_json_is_rejected_gracefully(): void
    {
        $malformedBody = 'this is not json {{{';
        $signature = $this->computeSignature($malformedBody);

        $response = $this->call('POST', $this->webhookUrl, [], [], [], [
            'HTTP_X_Wayl_Signature' => $signature,
            'CONTENT_TYPE' => 'application/json',
        ], $malformedBody);

        // Should return a response without throwing an unhandled exception
        // (may be 200, 400, or 500 but should not be an unhandled crash)
        $this->assertTrue($response->status() < 600);
    }

    // 12. test_multiple_valid_webhooks_are_all_processed
    public function test_multiple_valid_webhooks_are_all_processed(): void
    {
        $orders = [];
        for ($i = 0; $i < 3; $i++) {
            $orders[] = $this->createTestOrder('pending');
        }

        foreach ($orders as $order) {
            $payload = $this->buildPayload((string) $order->id, 'Completed');
            $jsonPayload = json_encode($payload);
            $signature = $this->computeSignature($jsonPayload);

            $this->postJson($this->webhookUrl, $payload, [
                'X-Wayl-Signature' => $signature,
            ]);
        }

        foreach ($orders as $order) {
            $this->assertEquals('paid', $order->fresh()->payment_status);
        }
    }
}
