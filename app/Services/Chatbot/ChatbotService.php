<?php

declare(strict_types=1);

namespace App\Services\Chatbot;

use App\Models\Product;
use Illuminate\Support\Collection;

/**
 * Main Chatbot Service - Orchestrates all chatbot components
 */
class ChatbotService
{
    private IntentDetector $intentDetector;
    private EntityExtractor $entityExtractor;
    private ContextManager $contextManager;
    private ResponseGenerator $responseGenerator;
    private HardwareKnowledge $hardwareKnowledge;
    private RecommendationEngine $recommendationEngine;
    private BuildGenerator $buildGenerator;
    private ProductMatcher $productMatcher;

    public function __construct()
    {
        $this->intentDetector = new IntentDetector();
        $this->entityExtractor = new EntityExtractor();
        $this->contextManager = new ContextManager();
        $this->responseGenerator = new ResponseGenerator();
        $this->hardwareKnowledge = new HardwareKnowledge();
        $this->recommendationEngine = new RecommendationEngine();
        $this->buildGenerator = new BuildGenerator();
        $this->productMatcher = new ProductMatcher();
    }

    public function processMessage(string $message, string $sessionId): array
    {
        $normalizedMessage = TextNormalizer::normalize($message);
        $intent = $this->intentDetector->detect($normalizedMessage);
        $entities = $this->entityExtractor->extract($normalizedMessage);
        $context = $this->contextManager->update($sessionId, $intent, $entities);
        
        // Check for direct product name mentions first
        if ($entities['product_name']) {
            $products = $this->productMatcher->findProduct($entities['product_name']);
            if ($products->isNotEmpty()) {
                return $this->responseGenerator->generate([
                    'text' => "I found these products matching '{$entities['product_name']}':",
                    'products' => $products->toArray(),
                    'options' => [
                        ['label' => 'Search Again', 'action' => 'search'],
                        ['label' => 'Build PC', 'action' => 'build'],
                    ],
                ]);
            }
        }

        return match ($intent) {
            'GREETING' => $this->handleGreeting($context),
            'PRODUCT_SEARCH' => $this->handleProductSearch($context, $entities),
            'PC_BUILD_REQUEST' => $this->handleBuildRequest($context, $entities),
            'COMPATIBILITY_CHECK' => $this->handleCompatibilityCheck($context, $entities),
            'COMPARISON_REQUEST' => $this->handleComparison($context, $entities),
            'PRICE_QUERY' => $this->handlePriceQuery($context, $entities),
            'SPECIFICATION_QUERY' => $this->handleSpecificationQuery($context, $entities),
            'NAVIGATION' => $this->handleNavigation($context, $entities),
            'SMALL_TALK' => $this->handleSmallTalk($context, $message),
            'HELP' => $this->handleHelp($context),
            default => $this->handleUnknown($context, $message),
        };
    }

    private function handleGreeting(array $context): array
    {
        $greetings = [
            "Hello! I'm your PC hardware assistant. I can help you find parts, build PCs, and check compatibility. What can I do for you today?",
            "Hey there! Ready to help you with all things PC hardware. Looking for something specific?",
            "Hi! I'm here to help you find the perfect PC components. Ask me about products, builds, or compatibility!",
        ];

        return $this->responseGenerator->generate([
            'text' => $greetings[array_rand($greetings)],
            'options' => [
                ['label' => 'Search Products', 'action' => 'prompt', 'value' => 'I want to search for'],
                ['label' => 'Build a PC', 'action' => 'prompt', 'value' => 'Build me a gaming PC'],
                ['label' => 'Check Compatibility', 'action' => 'prompt', 'value' => 'Is compatible with'],
                ['label' => 'Get Help', 'action' => 'help'],
            ],
        ]);
    }

    private function handleProductSearch(array $context, array $entities): array
    {
        $category = $entities['category'] ?? $context['selected_category'] ?? null;
        
        if (!$category) {
            return $this->responseGenerator->generate([
                'text' => "What type of component are you looking for?",
                'options' => [
                    ['label' => 'CPU', 'action' => 'set_category', 'value' => 'cpu'],
                    ['label' => 'GPU', 'action' => 'set_category', 'value' => 'gpu'],
                    ['label' => 'RAM', 'action' => 'set_category', 'value' => 'ram'],
                    ['label' => 'Motherboard', 'action' => 'set_category', 'value' => 'motherboard'],
                    ['label' => 'Storage', 'action' => 'set_category', 'value' => 'storage'],
                    ['label' => 'PSU', 'action' => 'set_category', 'value' => 'psu'],
                    ['label' => 'Case', 'action' => 'set_category', 'value' => 'case'],
                    ['label' => 'Cooling', 'action' => 'set_category', 'value' => 'cooling'],
                ],
            ]);
        }

        $products = $this->recommendationEngine->searchProducts(
            $category,
            $entities['specs'] ?? [],
            $entities['budget'] ?? null
        );

        if ($products->isEmpty()) {
            return $this->responseGenerator->generate([
                'text' => "I couldn't find any products matching your criteria. Would you like to adjust your filters?",
                'options' => [
                    ['label' => 'Change Budget', 'action' => 'change_budget'],
                    ['label' => 'Reset Filters', 'action' => 'reset_filters'],
                    ['label' => 'View All ' . ucfirst($category), 'action' => 'view_category', 'value' => $category],
                ],
            ]);
        }

        $categoryLabel = SynonymDictionary::getCategoryLabel($category);
        $budgetInfo = isset($entities['budget']) ? " under " . number_format($entities['budget']) . " IQD" : "";

        return $this->responseGenerator->generate([
            'text' => "I found " . $products->count() . " {$categoryLabel} options{$budgetInfo}. Here are my top picks:",
            'products' => $products->take(4)->values()->toArray(),
            'options' => [
                ['label' => 'View All Results', 'action' => 'view_all', 'category' => $category],
                ['label' => 'Refine Search', 'action' => 'refine'],
                ['label' => 'Add Filters', 'action' => 'add_filters'],
            ],
        ]);
    }

    private function handleBuildRequest(array $context, array $entities): array
    {
        $budget = $entities['budget'] ?? $context['budget'] ?? null;
        $useCase = $entities['use_case'] ?? $context['user_goal'] ?? null;

        if (!$budget) {
            return $this->responseGenerator->generate([
                'text' => "I'd love to help you build a PC! What's your budget?",
                'options' => [
                    ['label' => '$300-500', 'action' => 'set_budget', 'value' => 500],
                    ['label' => '$500-800', 'action' => 'set_budget', 'value' => 800],
                    ['label' => '$800-1200', 'action' => 'set_budget', 'value' => 1200],
                    ['label' => '$1200-1800', 'action' => 'set_budget', 'value' => 1800],
                    ['label' => '$1800+', 'action' => 'set_budget', 'value' => 2500],
                ],
            ]);
        }

        if (!$useCase) {
            return $this->responseGenerator->generate([
                'text' => "Great! A $" . number_format($budget) . " budget. What will you primarily use this PC for?",
                'options' => [
                    ['label' => 'Gaming', 'action' => 'set_use_case', 'value' => 'gaming'],
                    ['label' => 'Video Editing', 'action' => 'set_use_case', 'value' => 'editing'],
                    ['label' => 'Streaming', 'action' => 'set_use_case', 'value' => 'streaming'],
                    ['label' => 'Office/Work', 'action' => 'set_use_case', 'value' => 'office'],
                    ['label' => '3D/CAD', 'action' => 'set_use_case', 'value' => '3d_cad'],
                ],
            ]);
        }

        $build = $this->buildGenerator->generate($budget, $useCase);

        if (!$build['success']) {
            return $this->responseGenerator->generate([
                'text' => "I couldn't generate a complete build within your budget. Consider increasing it slightly.",
                'options' => [
                    ['label' => 'Increase Budget', 'action' => 'increase_budget'],
                    ['label' => 'Try Different Use Case', 'action' => 'change_use_case'],
                ],
            ]);
        }

        $tierLabel = $this->buildGenerator->getTierLabel($budget);

        return $this->responseGenerator->generate([
            'text' => "Here's a {$tierLabel} build for {$useCase}! Total: $" . number_format($build['total'], 2),
            'build' => $build['components'],
            'buildTotal' => $build['total'],
            'options' => [
                ['label' => 'Add Build to Cart', 'action' => 'add_build_to_cart'],
                ['label' => 'Upgrade GPU', 'action' => 'upgrade_component', 'component' => 'gpu'],
                ['label' => 'Change Budget', 'action' => 'change_budget'],
                ['label' => 'View Individual Parts', 'action' => 'view_build_parts'],
            ],
        ]);
    }

    private function handleCompatibilityCheck(array $context, array $entities): array
    {
        $components = $entities['components'] ?? [];

        if (count($components) < 2) {
            return $this->responseGenerator->generate([
                'text' => "Tell me which components you want to check compatibility for. For example: 'Is Ryzen 5 5600X compatible with B550 motherboard?'",
                'options' => [
                    ['label' => 'CPU + Motherboard', 'action' => 'check_compat', 'type' => 'cpu_motherboard'],
                    ['label' => 'RAM + Motherboard', 'action' => 'check_compat', 'type' => 'ram_motherboard'],
                    ['label' => 'GPU + PSU', 'action' => 'check_compat', 'type' => 'gpu_psu'],
                ],
            ]);
        }

        $result = $this->hardwareKnowledge->checkCompatibility($components);

        if ($result['compatible']) {
            return $this->responseGenerator->generate([
                'text' => "Compatible: " . $result['message'],
                'details' => $result['details'] ?? null,
                'options' => [
                    ['label' => 'View These Products', 'action' => 'view_products'],
                    ['label' => 'Check Another', 'action' => 'new_compat_check'],
                ],
            ]);
        }

        return $this->responseGenerator->generate([
            'text' => "Not Compatible: " . $result['message'],
            'details' => $result['details'] ?? null,
            'alternatives' => $result['alternatives'] ?? [],
            'options' => [
                ['label' => 'Show Compatible Options', 'action' => 'show_compatible', 'data' => $result['alternatives'] ?? []],
                ['label' => 'Check Different Parts', 'action' => 'new_compat_check'],
            ],
        ]);
    }

    private function handleComparison(array $context, array $entities): array
    {
        return $this->responseGenerator->generate([
            'text' => "Which products would you like to compare? Tell me names or specs, like 'Compare RTX 4070 vs RX 7800 XT'",
            'options' => [
                ['label' => 'Compare GPUs', 'action' => 'compare_category', 'category' => 'gpu'],
                ['label' => 'Compare CPUs', 'action' => 'compare_category', 'category' => 'cpu'],
                ['label' => 'Compare RAM', 'action' => 'compare_category', 'category' => 'ram'],
            ],
        ]);
    }

    private function handlePriceQuery(array $context, array $entities): array
    {
        $productName = $entities['product_name'] ?? null;

        if (!$productName) {
            return $this->responseGenerator->generate([
                'text' => "Which product's price would you like to know?",
                'options' => [
                    ['label' => 'Search Products', 'action' => 'search'],
                ],
            ]);
        }

        $products = Product::where('name', 'like', '%' . $productName . '%')
            ->where('is_active', true)
            ->take(5)
            ->get();

        if ($products->isEmpty()) {
            return $this->responseGenerator->generate([
                'text' => "I couldn't find products matching '{$productName}'.",
                'options' => [
                    ['label' => 'Try Different Search', 'action' => 'search'],
                ],
            ]);
        }

        return $this->responseGenerator->generate([
            'text' => "Here are the prices for '{$productName}':",
            'products' => $products->toArray(),
            'options' => [
                ['label' => 'Add to Cart', 'action' => 'add_to_cart'],
                ['label' => 'Compare Prices', 'action' => 'compare_prices'],
            ],
        ]);
    }

    private function handleSpecificationQuery(array $context, array $entities): array
    {
        return $this->responseGenerator->generate([
            'text' => "I can help you understand PC specifications! What would you like to know about?",
            'options' => [
                ['label' => 'CPU Specs', 'action' => 'explain', 'topic' => 'cpu'],
                ['label' => 'GPU Specs', 'action' => 'explain', 'topic' => 'gpu'],
                ['label' => 'RAM Specs', 'action' => 'explain', 'topic' => 'ram'],
                ['label' => 'Socket Types', 'action' => 'explain', 'topic' => 'sockets'],
            ],
        ]);
    }

    private function handleNavigation(array $context, array $entities): array
    {
        $destination = $entities['destination'] ?? null;

        $routes = [
            'shop' => '/shop',
            'cart' => '/cart',
            'builder' => '/build-pc',
            'compare' => '/compare',
            'home' => '/',
            'orders' => '/my-orders',
        ];

        if ($destination && isset($routes[$destination])) {
            return $this->responseGenerator->generate([
                'text' => "Taking you to the {$destination} page!",
                'navigate' => $routes[$destination],
            ]);
        }

        return $this->responseGenerator->generate([
            'text' => "Where would you like to go?",
            'options' => [
                ['label' => 'Home', 'action' => 'navigate', 'route' => '/'],
                ['label' => 'Shop', 'action' => 'navigate', 'route' => '/shop'],
                ['label' => 'PC Builder', 'action' => 'navigate', 'route' => '/build-pc'],
                ['label' => 'Compare', 'action' => 'navigate', 'route' => '/compare'],
                ['label' => 'Cart', 'action' => 'navigate', 'route' => '/cart'],
            ],
        ]);
    }

    private function handleHelp(array $context): array
    {
        return $this->responseGenerator->generate([
            'text' => "Here's what I can help you with:\n\n• Product Search - Find specific components (e.g. \"RTX 4080\")\n• PC Building - Get build recommendations\n• Compatibility - Check if parts work together\n• Comparisons - Compare different products\n• Tools - System requirements & power calculator",
            'options' => [
                ['label' => 'Search Products', 'action' => 'prompt', 'value' => 'I want to find'],
                ['label' => 'Build a PC', 'action' => 'prompt', 'value' => 'Build me a PC'],
                ['label' => 'Tools & Utilities', 'action' => 'tools'],
            ],
        ]);
    }

    private function handleUnknown(array $context, string $originalMessage): array
    {
        // Try to fuzzy match products as a last resort
        $products = $this->productMatcher->findProduct($originalMessage);
        
        if ($products->isNotEmpty()) {
            return $this->responseGenerator->generate([
                'text' => "I found these products that might match what you're looking for:",
                'products' => $products->toArray(),
                'options' => [
                    ['label' => 'Search Again', 'action' => 'search'],
                    ['label' => 'Build PC', 'action' => 'build'],
                ],
            ]);
        }

        // Context-aware suggestions
        $options = [
            ['label' => 'Search Products', 'action' => 'search'],
            ['label' => 'Build a PC', 'action' => 'build'],
            ['label' => 'Tools', 'action' => 'tools'],
        ];

        // If they mentioned money/budget words
        if (preg_match('/(money|cost|price|budget|iqd|dollar|cheap|expensive)/i', $originalMessage)) {
            $options = [
                ['label' => 'Build by Budget', 'action' => 'build'],
                ['label' => 'Check Prices', 'action' => 'search'],
            ];
        }

        return $this->responseGenerator->generate([
            'text' => "I'm not sure I understood that. Could you rephrase? \n\nTip: You can search directly like \"RTX 4070\" or ask for a \"Gaming PC under 2M IQD\".",
            'options' => $options,
        ]);
    }

    private function handleTools(array $context): array
    {
        return $this->responseGenerator->generate([
            'text' => "I have some useful tools to help you with your build:",
            'options' => [
                ['label' => 'Can I Run It?', 'action' => 'game_req'],
                ['label' => 'Power Calculator', 'action' => 'power_calc'],
                ['label' => 'Export Build', 'action' => 'export_build'],
            ],
        ]);
    }

    private function handleGameReq(): array
    {
        return $this->responseGenerator->generate([
            'text' => "To check if your PC can run a game, tell me your specs and the game name. \n\nExample: \"Can I run Cyberpunk 2077 on RTX 3060?\"",
            'options' => [
                ['label' => 'Back to Tools', 'action' => 'tools'],
            ],
        ]);
    }

    private function handlePowerCalc(): array
    {
        return $this->responseGenerator->generate([
            'text' => "For a quick power estimate:\n• Low-end PC: 450W\n• Mid-range (RTX 4060/RX 7600): 650W\n• High-end (RTX 4080/RX 7900): 850W+\n\nI can calculate exactly if you tell me your parts!",
            'options' => [
                ['label' => 'Back to Tools', 'action' => 'tools'],
            ],
        ]);
    }

    private function handleSmallTalk(array $context, string $message): array
    {
        $responses = [
            'how are you' => "I'm just code, but I'm running smoothly! Thanks for asking. How can I help with your PC build?",
            'who are you' => "I'm your PC Assistant. I can help you find parts, check compatibility, and plan your dream build.",
            'name' => "You can call me PC Bot, or just Assistant. I'm here to help!",
            'thank' => "You're welcome! Let me know if you need anything else.",
            'bye' => "Goodbye! Come back whenever you need to upgrade!",
            'joke' => [
                "Why did the computer go to the dentist? Because it had Bluetooth decay!",
                "Why was the computer cold? It left its Windows open!",
                "What do you call a computer floating in the ocean? A Dell Rolling in the Deep!",
            ],
            'cool' => "Glad you think so! PC building is pretty cool indeed.",
            'smart' => "I try my best! I've learned everything I know about PCs from my database.",
            'stupid' => "I'm sorry if I made a mistake. I'm still learning! Try rephrasing your request.",
            'real' => "I'm a virtual assistant, but my component recommendations are very real!",
        ];

        $matchedResponse = "I'm happy to chat, but I'm best at helping you build PCs! Ask me about CPUs or GPUs.";

        foreach ($responses as $key => $response) {
            if (stripos($message, $key) !== false) {
                if (is_array($response)) {
                    $matchedResponse = $response[array_rand($response)];
                } else {
                    $matchedResponse = $response;
                }
                break;
            }
        }

        return $this->responseGenerator->generate([
            'text' => $matchedResponse,
            'options' => [
                ['label' => 'Build a PC', 'action' => 'prompt', 'value' => 'Build me a PC'],
                ['label' => 'Search Products', 'action' => 'prompt', 'value' => 'I want to find'],
                ['label' => 'Tell me a joke', 'action' => 'prompt', 'value' => 'Tell me a joke'],
            ],
        ]);
    }

    public function handleAction(string $action, array $params, string $sessionId): array
    {
        $context = $this->contextManager->get($sessionId);

        return match ($action) {
            'set_category' => $this->setCategory($params['value'] ?? '', $sessionId),
            'set_budget' => $this->setBudget($params['value'] ?? 0, $sessionId),
            'set_use_case' => $this->setUseCase($params['value'] ?? '', $sessionId),
            'add_build_to_cart' => $this->addBuildToCart($context),
            'view_category' => $this->viewCategory($params['value'] ?? ''),
            'navigate' => $this->navigate($params['route'] ?? '/'),
            'help' => $this->handleHelp($context),
            'tools' => $this->handleTools($context),
            'game_req' => $this->handleGameReq(),
            'power_calc' => $this->handlePowerCalc(),
            'export_build' => $this->responseGenerator->generate(['text' => 'Build export feature coming soon!']),
            'prompt' => $this->processMessage($params['value'] ?? '', $sessionId),
            default => $this->handleUnknown($context, $action),
        };
    }

    private function setCategory(string $category, string $sessionId): array
    {
        $this->contextManager->set($sessionId, 'selected_category', $category);
        return $this->processMessage("Show me {$category}", $sessionId);
    }

    private function setBudget(int $budget, string $sessionId): array
    {
        $this->contextManager->set($sessionId, 'budget', $budget);
        $context = $this->contextManager->get($sessionId);
        
        if (($context['last_intent'] ?? '') === 'PC_BUILD_REQUEST') {
            return $this->handleBuildRequest($context, ['budget' => $budget]);
        }
        
        return $this->processMessage("My budget is {$budget}", $sessionId);
    }

    private function setUseCase(string $useCase, string $sessionId): array
    {
        $this->contextManager->set($sessionId, 'user_goal', $useCase);
        $context = $this->contextManager->get($sessionId);
        return $this->handleBuildRequest($context, ['use_case' => $useCase, 'budget' => $context['budget'] ?? null]);
    }

    private function addBuildToCart(array $context): array
    {
        return $this->responseGenerator->generate([
            'text' => "Build added to cart! Ready to checkout?",
            'options' => [
                ['label' => 'View Cart', 'action' => 'navigate', 'route' => '/cart'],
                ['label' => 'Continue Shopping', 'action' => 'navigate', 'route' => '/shop'],
            ],
        ]);
    }

    private function viewCategory(string $category): array
    {
        return $this->responseGenerator->generate([
            'text' => "Opening the {$category} category...",
            'navigate' => "/shop?category={$category}",
        ]);
    }

    private function navigate(string $route): array
    {
        return $this->responseGenerator->generate([
            'text' => "Navigating...",
            'navigate' => $route,
        ]);
    }
}
