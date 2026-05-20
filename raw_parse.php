<?php

function check_file_for_duplicates($path) {
    $content = file_get_contents($path);
    $tokens = token_get_all($content);
    
    $array_levels = [];
    $current_level = 0;
    
    $keys_at_level = [0 => []];
    $duplicates = [];
    
    $last_string = null;
    
    foreach ($tokens as $token) {
        if (is_array($token)) {
            $id = $token[0];
            $text = $token[1];
            
            if ($id === T_CONSTANT_ENCAPSED_STRING) {
                // remove quotes
                $last_string = strip_quotes($text);
            }
        } else {
            // single character tokens
            if ($token === '[') {
                $current_level++;
                $keys_at_level[$current_level] = [];
            } elseif ($token === ']') {
                unset($keys_at_level[$current_level]);
                $current_level--;
            } elseif ($token === '=') {
                // maybe part of =>
            } elseif ($token === '>') {
                // maybe part of =>
            }
        }
        
        // Let's track when a key is assigned
        // In associative arrays, we have T_CONSTANT_ENCAPSED_STRING followed by whitespace/T_DOUBLE_ARROW
    }
    
    // A simpler way: let's scan the file line by line and find any line matching:
    // 'key' => [ or 'key' => 'value'
    // Let's track key paths, like "nav.home"
    $lines = explode("\n", $content);
    $path_stack = [];
    $indent_stack = [];
    
    foreach ($lines as $i => $line) {
        $trimmed = trim($line);
        if ($trimmed === '' || $trimmed === '<?php' || $trimmed === 'return [' || $trimmed === '];') {
            continue;
        }
        
        // Measure indent
        preg_match('/^(\s*)/', $line, $m);
        $indent = strlen($m[1]);
        
        // Pop stack based on indent
        while (!empty($indent_stack) && end($indent_stack) >= $indent) {
            array_pop($indent_stack);
            array_pop($path_stack);
        }
        
        // Check for 'key' => [ or 'key' => 'value'
        if (preg_match('/^\s*[\'"]([^\'"]+)[\'"]\s*=>\s*(.*)$/', $line, $matches)) {
            $key = $matches[1];
            $rest = trim($matches[2]);
            
            $full_path = implode('.', array_merge($path_stack, [$key]));
            
            if ($rest === '[') {
                // Start of nested array
                $indent_stack[] = $indent;
                $path_stack[] = $key;
            }
            
            $all_keys[] = [
                'path' => $full_path,
                'line' => $i + 1,
                'content' => $trimmed
            ];
        }
    }
    
    $seen = [];
    $duplicates = [];
    foreach ($all_keys as $item) {
        $p = $item['path'];
        if (isset($seen[$p])) {
            $duplicates[] = [
                'path' => $p,
                'first_line' => $seen[$p]['line'],
                'first_content' => $seen[$p]['content'],
                'second_line' => $item['line'],
                'second_content' => $item['content']
            ];
        } else {
            $seen[$p] = $item;
        }
    }
    
    return [
        'duplicates' => $duplicates,
        'all' => $all_keys
    ];
}

function strip_quotes($str) {
    if ((strpos($str, "'") === 0 && strrpos($str, "'") === strlen($str) - 1) ||
        (strpos($str, '"') === 0 && strrpos($str, '"') === strlen($str) - 1)) {
        return substr($str, 1, -1);
    }
    return $str;
}

foreach (['en', 'ar', 'ku'] as $lang) {
    $res = check_file_for_duplicates("lang/{$lang}/messages.php");
    echo "=== {$lang} messages ===\n";
    echo "Total keys found: " . count($res['all']) . "\n";
    echo "Duplicate definitions found: " . count($res['duplicates']) . "\n";
    foreach ($res['duplicates'] as $dup) {
        echo "  Key: {$dup['path']}\n";
        echo "    Line {$dup['first_line']}: {$dup['first_content']}\n";
        echo "    Line {$dup['second_line']}: {$dup['second_content']}\n";
    }
    echo "\n";
}
