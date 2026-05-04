<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WaylWebhookController extends Controller
{
    public function handle(Request $request)
    {
        $payload = $request->all();
        $signature = $request->header('WEBHOOK-SIGNATURE');
        $secret = env('WAYL_WEBHOOK_SECRET', 'default_secret_string_min_10_chars');

        // Verify the signature
        // We use hash_hmac with sha256 to sign the JSON payload
        $expectedSignature = hash_hmac('sha256', $request->getContent(), $secret);

        if (!hash_equals($expectedSignature, $signature)) {
            Log::warning('Invalid Wayl Webhook Signature', ['payload' => $payload]);
            return response()->json(['error' => 'Invalid signature'], 400);
        }

        // Webhook structure usually contains data like $payload['data']['referenceId']
        // Check the exact payload in documentation or log it structure.
        // As per Wayl docs, it has the referenceId and status.
        $referenceId = $payload['data']['referenceId'] ?? null;
        $status = $payload['data']['status'] ?? null;

        if ($referenceId && $status === 'Completed') {
            $order = Order::find($referenceId);
            if ($order) {
                $order->update([
                    'payment_status' => 'paid',
                    'status' => 'processing'
                ]);
            }
        } elseif ($referenceId && $status === 'Failed') {
            $order = Order::find($referenceId);
            if ($order) {
                $order->update([
                    'payment_status' => 'failed',
                    'status' => 'cancelled' // or keep it pending
                ]);
            }
        }

        return response()->json(['success' => true]);
    }
}
