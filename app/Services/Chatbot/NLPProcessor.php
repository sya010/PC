<?php

declare(strict_types=1);

namespace App\Services\Chatbot;

/**
 * NLP Processor - Implements Vector Space Model algorithms
 * 
 * Uses TF-IDF (Term Frequency-Inverse Document Frequency) and
 * Cosine Similarity to mathematically determine intent similarity.
 */
class NLPProcessor
{
    private array $vocabulary = [];
    private array $idfVectors = []; // Inverse Document Frequency
    private array $documentVectors = [];
    private array $trainingData = [];
    
    /**
     * Stop words to filter out noise
     */
    private array $stopWords = [
        'a', 'an', 'the', 'is', 'at', 'in', 'on', 'to', 'for', 'of', 'with',
        'are', 'am', 'i', 'you', 'he', 'she', 'it', 'we', 'they', 'me', 'my',
        'do', 'does', 'did', 'have', 'has', 'had', 'can', 'could', 'would',
        'should', 'will', 'be', 'been', 'being', 'what', 'where', 'when', 'who',
        'why', 'how', 'please', 'thanks', 'thank', 'hi', 'hello', 'hey'
    ];

    /**
     * Train the model with labeled sentences
     */
    public function train(array $data): void
    {
        $this->trainingData = $data;
        $allDocuments = [];

        // 1. Build Vocabulary
        foreach ($data as $intent => $examples) {
            foreach ($examples as $example) {
                $tokens = $this->tokenize($example);
                foreach ($tokens as $token) {
                    $this->vocabulary[$token] = 0;
                }
                $allDocuments[] = ['intent' => $intent, 'tokens' => $tokens];
            }
        }
        $this->vocabulary = array_keys($this->vocabulary);
        sort($this->vocabulary);

        // 2. Calculate IDF
        $totalDocs = count($allDocuments);
        foreach ($this->vocabulary as $term) {
            $docsWithTerm = 0;
            foreach ($allDocuments as $doc) {
                if (in_array($term, $doc['tokens'])) {
                    $docsWithTerm++;
                }
            }
            $this->idfVectors[$term] = log($totalDocs / ($docsWithTerm ?: 1));
        }

        // 3. Create Document Vectors (TF-IDF)
        foreach ($allDocuments as $doc) {
            $this->documentVectors[] = [
                'intent' => $doc['intent'],
                'vector' => $this->createVector($doc['tokens'])
            ];
        }
    }

    /**
     * Predict the most likely intent for a given text
     */
    public function predict(string $text): ?string
    {
        $tokens = $this->tokenize($text);
        if (empty($tokens)) {
            return null;
        }

        $inputVector = $this->createVector($tokens);
        
        $bestIntent = null;
        $bestScore = 0.0;
        
        // Find closest match using Cosine Similarity
        foreach ($this->documentVectors as $doc) {
            $score = $this->cosineSimilarity($inputVector, $doc['vector']);
            
            if ($score > $bestScore) {
                $bestScore = $score;
                $bestIntent = $doc['intent'];
            }
        }

        // Threshold to avoid random guessing (0.2 is reasonable for short text)
        return ($bestScore > 0.2) ? $bestIntent : null;
    }

    /**
     * Preprocessing: Lowercase, remove punctuation, remove stop words
     */
    private function tokenize(string $text): array
    {
        $text = strtolower($text);
        // Keep only letters and numbers
        $text = preg_replace('/[^a-z0-9\s]/', '', $text);
        $tokens = explode(' ', $text);
        
        return array_values(array_filter($tokens, function($token) {
            return !empty($token) && !in_array($token, $this->stopWords);
        }));
    }

    /**
     * Create TF-IDF Vector for a set of tokens
     */
    private function createVector(array $tokens): array
    {
        $vector = array_fill_keys($this->vocabulary, 0.0);
        $termCounts = array_count_values($tokens);
        $totalTerms = count($tokens);

        foreach ($termCounts as $term => $count) {
            if (isset($this->vocabulary[$term]) || in_array($term, $this->vocabulary)) {
                // TF = count / total
                $tf = $count / $totalTerms;
                // IDF = idfVectors[term]
                $idf = $this->idfVectors[$term] ?? 0;
                
                $vector[$term] = $tf * $idf;
            }
        }
        
        // Ensure keys align with vocabulary order
        $orderedVector = [];
        foreach ($this->vocabulary as $term) {
            $orderedVector[] = $vector[$term] ?? 0.0;
        }

        return $orderedVector;
    }

    /**
     * Calculate Cosine Similarity between two vectors
     */
    private function cosineSimilarity(array $vec1, array $vec2): float
    {
        $dotProduct = 0.0;
        $magnitude1 = 0.0;
        $magnitude2 = 0.0;

        foreach ($vec1 as $i => $val1) {
            $val2 = $vec2[$i];
            $dotProduct += $val1 * $val2;
            $magnitude1 += $val1 * $val1;
            $magnitude2 += $val2 * $val2;
        }

        $magnitude1 = sqrt($magnitude1);
        $magnitude2 = sqrt($magnitude2);

        if ($magnitude1 * $magnitude2 == 0) {
            return 0.0;
        }

        return $dotProduct / ($magnitude1 * $magnitude2);
    }
}
