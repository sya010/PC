<?php

function analyze_raw_file($path) {
    $lines = file($path);
    $keys = [];
    foreach ($lines as $i => $line) {
        // Match lines like:   'key' => [   or   'key' => 'value',
        if (preg_match('/^\s*[\'"]([a-zA-Z0-9_-]+)[\'"]\s*=>/', $line, $matches)) {
            // Let's check the exact indentation
            preg_match('/^(\s*)/', $line, $indent_m);
            $indent = strlen($indent_m[1]);
            
            $key = $matches[1];
            $keys[] = [
                'key' => $key,
                'indent' => $indent,
                'line' => $i + 1,
                'content' => trim($line)
            ];
        }
    }
    return $keys;
}

foreach (['en', 'ar', 'ku'] as $lang) {
    $path = "lang/{$lang}/messages.php";
    $keys = analyze_raw_file($path);
    echo "=== {$path} ===\n";
    $counts = [];
    foreach ($keys as $k) {
        $counts[$k['key']][] = $k;
    }
    
    foreach ($counts as $name => $list) {
        if (count($list) > 1) {
            echo "Key '{$name}' defined " . count($list) . " times:\n";
            foreach ($list as $item) {
                echo "  Line {$item['line']} (indent {$item['indent']}): {$item['content']}\n";
            }
        }
    }
    echo "\n";
}
