<?php

declare(strict_types=1);

namespace App\Services\Chatbot;

use Illuminate\Support\Facades\Session;

/**
 * Context Manager - Maintains conversation state across messages
 * 
 * Stores and retrieves session-based conversation context including
 * selected categories, specs, budget, and conversation history.
 */
class ContextManager
{
    /**
     * Session key prefix for chatbot context
     */
    private const SESSION_PREFIX = 'chatbot_context_';

    /**
     * Maximum conversation history to keep
     */
    private const MAX_HISTORY = 20;

    /**
     * Default context structure
     */
    private array $defaultContext = [
        'selected_category' => null,
        'selected_specs' => [],
        'budget' => null,
        'performance_level' => null,
        'user_goal' => null,
        'last_intent' => null,
        'last_products' => [],
        'current_build' => [],
        'conversation_history' => [],
        'created_at' => null,
        'updated_at' => null,
    ];

    /**
     * Get the current context for a session
     */
    public function get(string $sessionId): array
    {
        $key = self::SESSION_PREFIX . $sessionId;
        $context = Session::get($key, $this->defaultContext);
        
        // Ensure all keys exist
        return array_merge($this->defaultContext, $context);
    }

    /**
     * Update context with new intent and entities
     */
    public function update(string $sessionId, string $intent, array $entities): array
    {
        $context = $this->get($sessionId);
        
        // Update last intent
        $context['last_intent'] = $intent;
        $context['updated_at'] = now()->toDateTimeString();
        
        if (!$context['created_at']) {
            $context['created_at'] = $context['updated_at'];
        }
        
        // Merge entities into context (only non-null values)
        if (!empty($entities['category'])) {
            $context['selected_category'] = $entities['category'];
        }
        
        if (!empty($entities['specs'])) {
            $context['selected_specs'] = array_merge(
                $context['selected_specs'] ?? [],
                $entities['specs']
            );
        }
        
        if (!empty($entities['budget'])) {
            $context['budget'] = $entities['budget'];
        }
        
        if (!empty($entities['use_case'])) {
            $context['user_goal'] = $entities['use_case'];
        }
        
        if (!empty($entities['performance_level'])) {
            $context['performance_level'] = $entities['performance_level'];
        }
        
        // Save updated context
        $this->save($sessionId, $context);
        
        return $context;
    }

    /**
     * Set a specific context value
     */
    public function set(string $sessionId, string $key, mixed $value): void
    {
        $context = $this->get($sessionId);
        $context[$key] = $value;
        $context['updated_at'] = now()->toDateTimeString();
        $this->save($sessionId, $context);
    }

    /**
     * Add a message to conversation history
     */
    public function addToHistory(string $sessionId, string $role, string $content, array $metadata = []): void
    {
        $context = $this->get($sessionId);
        
        $context['conversation_history'][] = [
            'role' => $role, // 'user' or 'bot'
            'content' => $content,
            'metadata' => $metadata,
            'timestamp' => now()->toDateTimeString(),
        ];
        
        // Trim history if too long
        if (count($context['conversation_history']) > self::MAX_HISTORY) {
            $context['conversation_history'] = array_slice(
                $context['conversation_history'],
                -self::MAX_HISTORY
            );
        }
        
        $this->save($sessionId, $context);
    }

    /**
     * Get conversation history
     */
    public function getHistory(string $sessionId, int $limit = 10): array
    {
        $context = $this->get($sessionId);
        $history = $context['conversation_history'] ?? [];
        
        return array_slice($history, -$limit);
    }

    /**
     * Store products from last search
     */
    public function setLastProducts(string $sessionId, array $products): void
    {
        $this->set($sessionId, 'last_products', $products);
    }

    /**
     * Store current build configuration
     */
    public function setCurrentBuild(string $sessionId, array $build): void
    {
        $this->set($sessionId, 'current_build', $build);
    }

    /**
     * Get current build configuration
     */
    public function getCurrentBuild(string $sessionId): array
    {
        $context = $this->get($sessionId);
        return $context['current_build'] ?? [];
    }

    /**
     * Clear context for a session
     */
    public function clear(string $sessionId): void
    {
        $key = self::SESSION_PREFIX . $sessionId;
        Session::forget($key);
    }

    /**
     * Clear only search-related context (keep history)
     */
    public function clearSearch(string $sessionId): void
    {
        $context = $this->get($sessionId);
        $context['selected_category'] = null;
        $context['selected_specs'] = [];
        $context['last_products'] = [];
        $this->save($sessionId, $context);
    }

    /**
     * Check if context has a specific value set
     */
    public function has(string $sessionId, string $key): bool
    {
        $context = $this->get($sessionId);
        return isset($context[$key]) && $context[$key] !== null;
    }

    /**
     * Get a specific context value
     */
    public function getValue(string $sessionId, string $key): mixed
    {
        $context = $this->get($sessionId);
        return $context[$key] ?? null;
    }

    /**
     * Save context to session
     */
    private function save(string $sessionId, array $context): void
    {
        $key = self::SESSION_PREFIX . $sessionId;
        Session::put($key, $context);
    }

    /**
     * Get context summary for debugging
     */
    public function getSummary(string $sessionId): array
    {
        $context = $this->get($sessionId);
        
        return [
            'category' => $context['selected_category'],
            'budget' => $context['budget'],
            'goal' => $context['user_goal'],
            'last_intent' => $context['last_intent'],
            'history_count' => count($context['conversation_history'] ?? []),
            'has_build' => !empty($context['current_build']),
        ];
    }
}
