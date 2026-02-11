<?php

declare(strict_types=1);

namespace App\Services\Chatbot;

/**
 * Text Normalizer - Cleans and normalizes user input
 */
class TextNormalizer
{
    private static array $contractions = [
        "i'm" => "i am", "you're" => "you are", "he's" => "he is",
        "she's" => "she is", "it's" => "it is", "we're" => "we are",
        "they're" => "they are", "i've" => "i have", "you've" => "you have",
        "we've" => "we have", "they've" => "they have", "i'd" => "i would",
        "you'd" => "you would", "he'd" => "he would", "she'd" => "she would",
        "we'd" => "we would", "they'd" => "they would", "i'll" => "i will",
        "you'll" => "you will", "he'll" => "he will", "she'll" => "she will",
        "we'll" => "we will", "they'll" => "they will", "isn't" => "is not",
        "aren't" => "are not", "wasn't" => "was not", "weren't" => "were not",
        "won't" => "will not", "wouldn't" => "would not", "don't" => "do not",
        "doesn't" => "does not", "didn't" => "did not", "can't" => "cannot",
        "couldn't" => "could not", "shouldn't" => "should not",
        "what's" => "what is", "that's" => "that is", "there's" => "there is",
    ];

    public static function normalize(string $text): string
    {
        $text = strtolower(trim($text));
        $text = preg_replace('/\s+/', ' ', $text);
        
        foreach (self::$contractions as $contraction => $expanded) {
            $text = str_replace($contraction, $expanded, $text);
        }
        
        // Remove special characters but keep currency symbols and dots/commas for numbers
        $text = preg_replace('/[^\w\s\$\-\.\,]+/', '', $text);
        
        return trim($text);
    }

    /**
     * Calculate similarity between two strings (0.0 to 1.0)
     */
    public static function similarity(string $str1, string $str2): float
    {
        $str1 = self::normalize($str1);
        $str2 = self::normalize($str2);
        
        if (empty($str1) || empty($str2)) {
            return 0.0;
        }
        
        $lev = levenshtein($str1, $str2);
        $maxLength = max(strlen($str1), strlen($str2));
        
        return 1.0 - ($lev / $maxLength);
    }

    /**
     * Check if text contains a keyword with fuzzy matching tolerance
     */
    public static function fuzzyContains(string $text, string $keyword, float $threshold = 0.8): bool
    {
        $text = self::normalize($text);
        $keyword = self::normalize($keyword);
        
        // Direct match check first
        if (str_contains($text, $keyword)) {
            return true;
        }
        
        // Tokenize text
        $words = explode(' ', $text);
        
        foreach ($words as $word) {
            if (self::similarity($word, $keyword) >= $threshold) {
                return true;
            }
        }
        
        return false;
    }

    /**
     * Parse IQD currency from text (supports 'k', 'm', 'million' suffixes)
     */
    public static function extractIQD(string $text): ?int
    {
        $text = strtolower($text);
        
        // Pattern for "500k", "1.5m", "2 million", etc.
        if (preg_match('/(\d+(?:\.\d+)?)\s*(k|m|million|mil)/i', $text, $matches)) {
            $amount = (float) $matches[1];
            $suffix = strtolower($matches[2]);
            
            if ($suffix === 'k') {
                return (int) ($amount * 1000);
            }
            if (in_array($suffix, ['m', 'million', 'mil'])) {
                return (int) ($amount * 1000000);
            }
        }
        
        // Pattern for raw numbers "500,000" or "500000"
        if (preg_match('/(\d{1,3}(?:,\d{3})+|\d{4,})/', $text, $matches)) {
            return (int) str_replace(',', '', $matches[1]);
        }

        return null;
    }
}
