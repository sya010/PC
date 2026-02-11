<?php

declare(strict_types=1);

namespace App\Services\Chatbot;

/**
 * Response Generator - Creates structured responses for the chatbot
 */
class ResponseGenerator
{
    public function generate(array $data): array
    {
        $response = [
            'success' => true,
            'text' => $data['text'] ?? '',
            'timestamp' => now()->toDateTimeString(),
        ];

        if (!empty($data['products'])) {
            $response['products'] = $this->formatProducts($data['products']);
        }

        if (!empty($data['build'])) {
            $response['build'] = $data['build'];
            $response['buildTotal'] = $data['buildTotal'] ?? 0;
        }

        if (!empty($data['options'])) {
            $response['options'] = $data['options'];
        }

        if (isset($data['navigate'])) {
            $response['navigate'] = $data['navigate'];
        }

        if (isset($data['details'])) {
            $response['details'] = $data['details'];
        }

        return $response;
    }

    private function formatProducts(array $products): array
    {
        return array_map(fn($p) => [
            'id' => $p['id'] ?? 0,
            'name' => $p['name'] ?? 'Unknown',
            'price' => $p['price'] ?? 0,
            'formatted_price' => '$' . number_format($p['price'] ?? 0, 2),
            'image' => $p['image'] ?? '/images/placeholder.png',
            'category' => $p['category'] ?? '',
        ], $products);
    }

    public function error(string $message): array
    {
        return [
            'success' => false,
            'text' => $message,
            'timestamp' => now()->toDateTimeString(),
        ];
    }
}
