<?php

declare(strict_types=1);

namespace App\Livewire;

use Livewire\Component;
use App\Services\Chatbot\ChatbotService;
use App\Models\Product;
use Illuminate\Support\Str;

/**
 * Chatbot Livewire Component
 */
class Chatbot extends Component
{
    public string $userMessage = '';
    public array $messages = [];
    public bool $isOpen = false;
    public bool $isTyping = false;
    public string $sessionId = '';
    
    // State for build flow
    public ?int $pendingBudget = null;
    public ?string $pendingUseCase = null;

    protected $listeners = ['chatbotAction' => 'handleAction'];

    public function mount(): void
    {
        $this->sessionId = session()->getId() ?? Str::uuid()->toString();
        $this->initializeChat();
    }

    private function initializeChat(): void
    {
        $this->pendingBudget = null;
        $this->pendingUseCase = null;
        
        $this->messages = [[
            'role' => 'bot',
            'content' => "Hi! I'm your PC hardware assistant. I can help you find parts, build PCs, and check compatibility. What can I do for you?",
            'options' => [
                ['label' => 'Search Products', 'action' => 'search', 'params' => []],
                ['label' => 'Build a PC', 'action' => 'build', 'params' => []],
                ['label' => 'Check Compatibility', 'action' => 'compatibility', 'params' => []],
                ['label' => 'Get Help', 'action' => 'help', 'params' => []],
            ],
            'products' => [],
            'build' => null,
            'buildTotal' => null,
            'timestamp' => now()->toDateTimeString(),
        ]];
    }

    public function toggleChat(): void
    {
        $this->isOpen = !$this->isOpen;
    }

    public function sendMessage(): void
    {
        if (empty(trim($this->userMessage))) {
            return;
        }

        $message = trim($this->userMessage);
        $this->userMessage = '';

        $this->messages[] = [
            'role' => 'user',
            'content' => $message,
            'timestamp' => now()->toDateTimeString(),
        ];

        $this->isTyping = true;

        try {
            $chatbot = new ChatbotService();
            $response = $chatbot->processMessage($message, $this->sessionId);
            $this->addBotMessage($response);
        } catch (\Exception $e) {
            $this->addBotMessage([
                'text' => 'Sorry, something went wrong. Please try again.',
                'options' => [['label' => 'Get Help', 'action' => 'help', 'params' => []]],
            ]);
        }

        $this->isTyping = false;
    }

    public function quickAction(string $action): void
    {
        $this->handleAction($action, []);
    }

    public function handleAction(string $action, array $params = []): void
    {
        // Search - show category options
        if ($action === 'search' || $action === 'gpus' || $action === 'cpus') {
            if ($action === 'gpus') {
                $this->searchCategory('GPU');
                return;
            }
            if ($action === 'cpus') {
                $this->searchCategory('CPU');
                return;
            }
            
            $this->addBotMessage([
                'text' => 'What type of component are you looking for?',
                'options' => [
                    ['label' => 'CPUs', 'action' => 'set_category', 'params' => ['value' => 'CPU']],
                    ['label' => 'GPUs', 'action' => 'set_category', 'params' => ['value' => 'GPU']],
                    ['label' => 'RAM', 'action' => 'set_category', 'params' => ['value' => 'RAM']],
                    ['label' => 'Motherboards', 'action' => 'set_category', 'params' => ['value' => 'Motherboard']],
                    ['label' => 'Storage', 'action' => 'set_category', 'params' => ['value' => 'Storage']],
                    ['label' => 'PSU', 'action' => 'set_category', 'params' => ['value' => 'PSU']],
                    ['label' => 'Cases', 'action' => 'set_category', 'params' => ['value' => 'Case']],
                    ['label' => 'Cooling', 'action' => 'set_category', 'params' => ['value' => 'Cooling']],
                    ['label' => 'Monitors', 'action' => 'set_category', 'params' => ['value' => 'Monitor']],
                    ['label' => 'Peripherals', 'action' => 'set_category', 'params' => ['value' => 'Keyboard']],
                ],
            ]);
            return;
        }

        // Set category - search products
        if ($action === 'set_category') {
            $category = $params['value'] ?? 'GPU';
            $this->searchCategory($category);
            return;
        }

        // Build - start build flow
        if ($action === 'build') {
            $this->pendingBudget = null;
            $this->pendingUseCase = null;
            
            $this->addBotMessage([
                'text' => "I'd love to help you build a PC! What's your budget?",
                'options' => [
                    ['label' => '500K-1M IQD', 'action' => 'set_budget', 'params' => ['value' => 1000000]],
                    ['label' => '1M-2M IQD', 'action' => 'set_budget', 'params' => ['value' => 2000000]],
                    ['label' => '2M-3M IQD', 'action' => 'set_budget', 'params' => ['value' => 3000000]],
                    ['label' => '3M-5M IQD', 'action' => 'set_budget', 'params' => ['value' => 5000000]],
                    ['label' => '5M+ IQD', 'action' => 'set_budget', 'params' => ['value' => 8000000]],
                ],
            ]);
            return;
        }

        // Set budget for build
        if ($action === 'set_budget') {
            $this->pendingBudget = (int) ($params['value'] ?? 2000000);
            $formatted = number_format($this->pendingBudget) . ' IQD';
            
            $this->addBotMessage([
                'text' => "Great! A {$formatted} budget. What will you primarily use this PC for?",
                'options' => [
                    ['label' => 'Gaming', 'action' => 'set_use_case', 'params' => ['value' => 'gaming']],
                    ['label' => 'Video Editing', 'action' => 'set_use_case', 'params' => ['value' => 'editing']],
                    ['label' => 'Streaming', 'action' => 'set_use_case', 'params' => ['value' => 'streaming']],
                    ['label' => 'Office/Work', 'action' => 'set_use_case', 'params' => ['value' => 'office']],
                    ['label' => '3D/CAD', 'action' => 'set_use_case', 'params' => ['value' => '3d_cad']],
                ],
            ]);
            return;
        }

        // Set use case and generate build
        if ($action === 'set_use_case') {
            $this->pendingUseCase = $params['value'] ?? 'gaming';
            $budget = $this->pendingBudget ?? 2000000;
            
            $this->generateBuild($budget, $this->pendingUseCase);
            return;
        }

        // Compatibility
        if ($action === 'compatibility' || $action === 'compat') {
            $this->addBotMessage([
                'text' => "I can check component compatibility. What would you like to check?",
                'options' => [
                    ['label' => 'CPU + Motherboard', 'action' => 'compat_check', 'params' => ['type' => 'cpu_mb']],
                    ['label' => 'RAM + Motherboard', 'action' => 'compat_check', 'params' => ['type' => 'ram_mb']],
                    ['label' => 'GPU + PSU', 'action' => 'compat_check', 'params' => ['type' => 'gpu_psu']],
                    ['label' => 'Full Build Check', 'action' => 'compat_check', 'params' => ['type' => 'full']],
                ],
            ]);
            return;
        }

        // Compatibility check types
        if ($action === 'compat_check') {
            $type = $params['type'] ?? 'cpu_mb';
            $messages = [
                'cpu_mb' => "For CPU + Motherboard compatibility:\n\n• Intel 12th/13th/14th Gen uses LGA1700 socket\n• Intel 10th/11th Gen uses LGA1200 socket\n• AMD Ryzen 5000/7000 series uses AM5\n• AMD Ryzen 3000/5000 uses AM4\n\nMake sure your CPU socket matches your motherboard socket!",
                'ram_mb' => "For RAM + Motherboard compatibility:\n\n• DDR5 RAM needs DDR5 motherboard\n• DDR4 RAM needs DDR4 motherboard\n• Check max RAM speed support\n• Check max capacity per slot\n\nDDR4 and DDR5 are NOT interchangeable!",
                'gpu_psu' => "For GPU + PSU compatibility:\n\n• RTX 4090: 850W+ recommended\n• RTX 4080: 750W+ recommended\n• RTX 4070: 650W+ recommended\n• RX 7900 XTX: 800W+ recommended\n\nAlways check GPU power connector requirements!",
                'full' => "For a full build, ensure:\n\n• CPU socket matches motherboard\n• RAM type matches motherboard\n• PSU has enough wattage for GPU\n• Case fits GPU length and cooler height\n• Motherboard form factor fits case",
            ];
            
            $this->addBotMessage([
                'text' => $messages[$type] ?? $messages['cpu_mb'],
                'options' => [
                    ['label' => 'Check Another', 'action' => 'compatibility', 'params' => []],
                    ['label' => 'Build a PC', 'action' => 'build', 'params' => []],
                    ['label' => 'Search Products', 'action' => 'search', 'params' => []],
                ],
            ]);
            return;
        }

        // Help
        if ($action === 'help') {
            $this->addBotMessage([
                'text' => "Here's what I can help you with:\n\n• Product Search - Find CPUs, GPUs, RAM, etc.\n• PC Building - Get build recommendations by budget\n• Compatibility - Check if parts work together\n• Navigation - Browse the shop",
                'options' => [
                    ['label' => 'Search Products', 'action' => 'search', 'params' => []],
                    ['label' => 'Build a PC', 'action' => 'build', 'params' => []],
                    ['label' => 'Check Compatibility', 'action' => 'compatibility', 'params' => []],
                ],
            ]);
            return;
        }

        // View all results - navigate to shop
        if ($action === 'view_all' || $action === 'view_category') {
            $category = $params['category'] ?? $params['value'] ?? '';
            $this->redirect(route('shop', ['category' => $category]));
            return;
        }

        // Navigate
        if ($action === 'navigate') {
            $route = $params['route'] ?? '/shop';
            $this->redirect($route);
            return;
        }

        // Prompt - send as message
        if ($action === 'prompt') {
            $this->userMessage = $params['value'] ?? '';
            $this->sendMessage();
            return;
        }

        // Default - show help
        $this->addBotMessage([
            'text' => "I'm not sure what you meant. Here are some things I can help with:",
            'options' => [
                ['label' => 'Search Products', 'action' => 'search', 'params' => []],
                ['label' => 'Build a PC', 'action' => 'build', 'params' => []],
                ['label' => 'Get Help', 'action' => 'help', 'params' => []],
            ],
        ]);
    }

    private function searchCategory(string $category): void
    {
        $products = Product::where('category', $category)
            ->where('is_active', true)
            ->orderBy('price', 'asc')
            ->limit(6)
            ->get()
            ->toArray();

        if (empty($products)) {
            $this->addBotMessage([
                'text' => "I couldn't find any {$category} products right now. Try another category?",
                'options' => [
                    ['label' => 'View All Categories', 'action' => 'search', 'params' => []],
                    ['label' => 'Build a PC', 'action' => 'build', 'params' => []],
                ],
            ]);
            return;
        }

        $this->addBotMessage([
            'text' => "Here are some {$category} options I found:",
            'products' => $products,
            'options' => [
                ['label' => 'View All ' . $category, 'action' => 'view_category', 'params' => ['category' => $category]],
                ['label' => 'Different Category', 'action' => 'search', 'params' => []],
                ['label' => 'Build a PC', 'action' => 'build', 'params' => []],
            ],
        ]);
    }

    private function generateBuild(int $budget, string $useCase): void
    {
        // Budget allocation based on use case
        $allocations = [
            'gaming' => ['GPU' => 0.35, 'CPU' => 0.20, 'Motherboard' => 0.12, 'RAM' => 0.10, 'Storage' => 0.08, 'PSU' => 0.08, 'Case' => 0.07],
            'editing' => ['CPU' => 0.30, 'GPU' => 0.25, 'RAM' => 0.15, 'Motherboard' => 0.12, 'Storage' => 0.10, 'PSU' => 0.05, 'Case' => 0.03],
            'streaming' => ['GPU' => 0.30, 'CPU' => 0.25, 'RAM' => 0.12, 'Motherboard' => 0.12, 'Storage' => 0.10, 'PSU' => 0.06, 'Case' => 0.05],
            'office' => ['CPU' => 0.25, 'RAM' => 0.15, 'Motherboard' => 0.15, 'Storage' => 0.20, 'GPU' => 0.10, 'PSU' => 0.08, 'Case' => 0.07],
            '3d_cad' => ['GPU' => 0.30, 'CPU' => 0.28, 'RAM' => 0.15, 'Motherboard' => 0.12, 'Storage' => 0.08, 'PSU' => 0.05, 'Case' => 0.02],
        ];

        $allocation = $allocations[$useCase] ?? $allocations['gaming'];
        $build = [];
        $totalPrice = 0;

        foreach ($allocation as $category => $percent) {
            $maxPrice = $budget * $percent * 1.5; // Allow 50% flexibility for better parts
            
            $product = Product::where('category', $category)
                ->where('is_active', true)
                ->where('price', '<=', $maxPrice)
                ->orderByDesc('price')
                ->first();

            if ($product) {
                $build[$category] = [
                    'id' => $product->id,
                    'name' => $product->name,
                    'price' => $product->price,
                ];
                $totalPrice += $product->price;
            }
        }

        if (count($build) < 4) {
            $budgetFormatted = number_format($budget) . ' IQD';
            $this->addBotMessage([
                'text' => "I couldn't find enough components for a {$budgetFormatted} build. Try a higher budget?",
                'options' => [
                    ['label' => '2M-3M IQD', 'action' => 'set_budget', 'params' => ['value' => 3000000]],
                    ['label' => '3M-5M IQD', 'action' => 'set_budget', 'params' => ['value' => 5000000]],
                    ['label' => '5M+ IQD', 'action' => 'set_budget', 'params' => ['value' => 8000000]],
                ],
            ]);
            return;
        }

        $tierLabel = match(true) {
            $budget <= 1000000 => 'Entry Level',
            $budget <= 2000000 => 'Budget',
            $budget <= 3000000 => 'Mid-Range',
            $budget <= 5000000 => 'High-End',
            default => 'Enthusiast',
        };

        $useCaseLabels = [
            'gaming' => 'Gaming',
            'editing' => 'Video Editing',
            'streaming' => 'Streaming',
            'office' => 'Office/Work',
            '3d_cad' => '3D/CAD',
        ];
        $useCaseLabel = $useCaseLabels[$useCase] ?? 'Gaming';

        $this->addBotMessage([
            'text' => "Here's a {$tierLabel} {$useCaseLabel} build:",
            'build' => $build,
            'buildTotal' => $totalPrice,
            'options' => [
                ['label' => 'Go to PC Builder', 'action' => 'navigate', 'params' => ['route' => '/build-pc']],
                ['label' => 'Different Budget', 'action' => 'build', 'params' => []],
                ['label' => 'Search Parts', 'action' => 'search', 'params' => []],
            ],
        ]);
    }

    private function addBotMessage(array $response): void
    {
        $this->messages[] = [
            'role' => 'bot',
            'content' => $response['text'] ?? '',
            'products' => $response['products'] ?? [],
            'build' => $response['build'] ?? null,
            'buildTotal' => $response['buildTotal'] ?? null,
            'options' => $response['options'] ?? [],
            'timestamp' => $response['timestamp'] ?? now()->toDateTimeString(),
        ];
    }

    public function clearChat(): void
    {
        $this->messages = [];
        $this->userMessage = '';
        $this->isTyping = false;
        $this->pendingBudget = null;
        $this->pendingUseCase = null;
        
        session()->forget('chatbot_context_' . $this->sessionId);
        $this->sessionId = Str::uuid()->toString();
        $this->initializeChat();
    }

    public function render()
    {
        return view('livewire.chatbot');
    }
}
