<?php

declare(strict_types=1);

namespace App\Services\Chatbot;

/**
 * Intent Detector - Classifies user messages into action intents
 * 
 * Uses keyword matching and pattern recognition to determine
 * what the user wants to accomplish.
 */
class IntentDetector
{
    /**
     * Intent patterns with associated keywords
     */
    private array $intentPatterns = [
        'GREETING' => [
            'keywords' => ['hi', 'hello', 'hey', 'good morning', 'good evening', 'howdy', 'greetings'],
            'patterns' => ['/^(hi|hello|hey)\b/i'],
            'priority' => 1,
        ],
        'HELP' => [
            'keywords' => ['help', 'assist', 'support', 'what can you do', 'how to use', 'guide'],
            'patterns' => ['/help me/i', '/what can you/i', '/how do i/i'],
            'priority' => 2,
        ],
        'PC_BUILD_REQUEST' => [
            'keywords' => ['build', 'setup', 'rig', 'gaming pc', 'editing pc', 'workstation', 'assemble'],
            'patterns' => ['/build\s*(me\s*)?(a\s*)?/i', '/gaming\s*(pc|setup|rig)/i', '/pc\s*build/i'],
            'priority' => 10,
        ],
        'COMPATIBILITY_CHECK' => [
            'keywords' => ['compatible', 'compatibility', 'work with', 'fit', 'match', 'support'],
            'patterns' => ['/is\s+.*\s+compatible/i', '/does\s+.*\s+work\s+with/i', '/will\s+.*\s+fit/i', '/can\s+i\s+use/i'],
            'priority' => 9,
        ],
        'COMPARISON_REQUEST' => [
            'keywords' => ['compare', 'vs', 'versus', 'difference', 'better', 'which one'],
            'patterns' => ['/compare\s+/i', '/\s+vs\.?\s+/i', '/difference\s+between/i', '/which\s+(is|one)\s+better/i'],
            'priority' => 8,
        ],
        'PRODUCT_SEARCH' => [
            'keywords' => ['find', 'search', 'show', 'looking for', 'want', 'need', 'buy', 'get', 'browse'],
            'patterns' => ['/show\s+me/i', '/looking\s+for/i', '/i\s+want/i', '/i\s+need/i', '/find\s+/i'],
            'priority' => 7,
        ],
        'PRICE_QUERY' => [
            'keywords' => ['price', 'cost', 'how much', 'budget', 'expensive', 'cheap', 'affordable'],
            'patterns' => ['/how\s+much/i', '/what.*price/i', '/cost\s+of/i'],
            'priority' => 6,
        ],
        'SPECIFICATION_QUERY' => [
            'keywords' => ['specs', 'specifications', 'features', 'details', 'what is', 'explain'],
            'patterns' => ['/what\s+is\s+(a\s+)?/i', '/explain\s+/i', '/tell\s+me\s+about/i'],
            'priority' => 5,
        ],
        'NAVIGATION' => [
            'keywords' => ['go to', 'take me', 'navigate', 'open', 'show page', 'visit'],
            'patterns' => ['/go\s+to/i', '/take\s+me\s+to/i', '/open\s+/i', '/navigate\s+to/i'],
            'priority' => 4,
        ],
        'SMALL_TALK' => [
            'keywords' => ['how are you', 'who are you', 'names', 'thanks', 'thank you', 'thx', 'bye', 'goodbye', 'joke', 'funny', 'cool', 'awesome', 'bad', 'stupid', 'smart', 'bot', 'artificial', 'intelligence', 'real', 'human', 'morning', 'night', 'evening'],
            'patterns' => [
                '/how\s+are\s+you/i',
                '/who\s+are\s+you/i',
                '/what\s+is\s+your\s+name/i',
                '/tell\s+me\s+a\s+joke/i',
                '/(thank|thanks)/i',
                '/(bye|goodbye|cya)/i',
                '/you\s+are\s+(cool|smart|stupid|bad|awesome)/i',
            ],
            'priority' => 3,
        ],
    ];
    
    private NLPProcessor $nlpProcessor;

    public function __construct()
    {
        $this->nlpProcessor = new NLPProcessor();
        $this->trainNLPModel();
    }

    /**
     * Train the NLP model with example sentences
     */
    private function trainNLPModel(): void
    {
        $trainingData = [
            'GREETING' => [
                'hello bot', 'hi there', 'good morning', 'good evening', 'hey', 
                'greetings', 'nice to meet you', 'anyone there'
            ],
            'PRODUCT_SEARCH' => [
                'find me a cpu', 'looking for a graphics card', 'search for rtx', 
                'do you have intel processors', 'i need a new gpu', 'show me motherboards',
                'find ryzen', 'search for corsair ram', 'products under 500k'
            ],
            'PC_BUILD_REQUEST' => [
                'i want to build a pc', 'help me build a computer', 'suggest a rig',
                'gaming pc build', 'workstation setup', 'computer for editing',
                'build me a gaming machine', 'budget build 2m iqd', 'create a computer'
            ],
            'COMPATIBILITY_CHECK' => [
                'will this work with that', 'check compatibility', 'are these parts compatible',
                'does this cpu fit this motherboard', 'is the psu enough', 'check my build'
            ],
            'PRICE_QUERY' => [
                'how much does this cost', 'what is the price', 'check price', 
                'is this expensive', 'cheapest option', 'cost of 4090'
            ],
            'SMALL_TALK' => [
                'how are you doing', 'who created you', 'tell me a joke', 'you are cool',
                'thanks for the help', 'bye bye', 'see you later', 'goodnight',
                'what is your name', 'are you a human'
            ],
            'HELP' => [
                'i need help', 'what can you do', 'show commands', 'help me please',
                'how do i use this'
            ]
        ];

        $this->nlpProcessor->train($trainingData);
    }

    /**
     * Category keywords for context
     */
    private array $categoryKeywords = [
        'cpu' => ['cpu', 'processor', 'ryzen', 'intel', 'i3', 'i5', 'i7', 'i9', 'amd'],
        'gpu' => ['gpu', 'graphics', 'video card', 'rtx', 'gtx', 'radeon', 'rx', 'nvidia', 'amd'],
        'ram' => ['ram', 'memory', 'ddr4', 'ddr5', '16gb', '32gb', '64gb'],
        'motherboard' => ['motherboard', 'mobo', 'mainboard', 'b550', 'b650', 'x570', 'z690', 'z790'],
        'storage' => ['storage', 'ssd', 'hdd', 'nvme', 'hard drive', 'disk'],
        'psu' => ['psu', 'power supply', 'watt', 'power unit'],
        'case' => ['case', 'tower', 'cabinet', 'chassis', 'enclosure'],
        'cooling' => ['cooling', 'cooler', 'fan', 'aio', 'liquid cooling', 'heatsink'],
    ];

    /**
     * Detect the primary intent from a normalized message
     */
    public function detect(string $message): string
    {
        $message = strtolower(trim($message));
        $scores = [];

        // 1. Try Pattern Matching (High Precision)
        foreach ($this->intentPatterns as $intent => $config) {
            $score = 0;

            // Check keywords
            foreach ($config['keywords'] as $keyword) {
                if (str_contains($message, $keyword)) {
                    $score += 10;
                }
            }

            // Check patterns (higher weight)
            foreach ($config['patterns'] as $pattern) {
                if (preg_match($pattern, $message)) {
                    $score += 25;
                }
            }

            // Apply priority modifier
            $score += $config['priority'];

            $scores[$intent] = $score;
        }

        // Get the intent with the highest score
        arsort($scores);
        $topIntent = array_key_first($scores);
        
        // High confidence match via patterns?
        if ($scores[$topIntent] >= 15) {
             return $topIntent;
        }

        // 2. Try NLP Algorithmic Prediction (High Recall / Understanding)
        // If pattern matching was weak, ask the brain
        $predictedIntent = $this->nlpProcessor->predict($message);
        
        if ($predictedIntent) {
            return $predictedIntent;
        }

        // 3. Fallback to category association
        if ($this->containsCategoryKeyword($message)) {
            return 'PRODUCT_SEARCH';
        }

        return 'UNKNOWN';
    }

    /**
     * Check if message contains any category keywords
     */
    private function containsCategoryKeyword(string $message): bool
    {
        foreach ($this->categoryKeywords as $category => $keywords) {
            foreach ($keywords as $keyword) {
                if (str_contains($message, $keyword)) {
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * Get confidence score for detected intent
     */
    public function getConfidence(string $message, string $intent): float
    {
        $message = strtolower(trim($message));
        $config = $this->intentPatterns[$intent] ?? null;

        if (!$config) {
            return 0.0;
        }

        $maxScore = (count($config['keywords']) * 10) + (count($config['patterns']) * 25) + 10;
        $score = 0;

        foreach ($config['keywords'] as $keyword) {
            if (str_contains($message, $keyword)) {
                $score += 10;
            }
        }

        foreach ($config['patterns'] as $pattern) {
            if (preg_match($pattern, $message)) {
                $score += 25;
            }
        }

        return min(1.0, $score / $maxScore);
    }

    /**
     * Get all possible intents with their scores
     */
    public function getAllIntentScores(string $message): array
    {
        $scores = [];
        foreach (array_keys($this->intentPatterns) as $intent) {
            $scores[$intent] = $this->getConfidence($message, $intent);
        }
        arsort($scores);
        return $scores;
    }
}
