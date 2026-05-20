<?php

function get_top_level_keys_raw($file) {
    $content = file_get_contents($file);
    $tokens = token_get_all($content);
    
    $keys = [];
    $depth = 0;
    
    foreach ($tokens as $i => $token) {
        if (is_array($token)) {
            $id = $token[0];
            $text = $token[1];
            
            if ($id === T_CONSTANT_ENCAPSED_STRING && $depth === 1) {
                // We are inside the main returned array, but outside nested arrays
                // Let's verify if the next non-whitespace token is a double arrow (=>)
                $next_arrow = false;
                for ($j = $i + 1; $j < count($tokens); $j++) {
                    $t = $tokens[$j];
                    if (is_array($t)) {
                        if ($t[0] === T_WHITESPACE || $t[0] === T_COMMENT || $t[0] === T_DOC_COMMENT) {
                            continue;
                        }
                        break;
                    } else {
                        if ($t === '=') {
                            // Check if next is >
                            if (isset($tokens[$j+1]) && $tokens[$j+1] === '>') {
                                $next_arrow = true;
                            }
                        } elseif ($t === T_DOUBLE_ARROW) {
                            $next_arrow = true;
                        }
                        break;
                    }
                }
                
                if ($next_arrow) {
                    $key = strip_quotes($text);
                    $keys[] = [
                        'key' => $key,
                        'line' => $token[2],
                        'text' => $text
                    ];
                }
            }
        } else {
            if ($token === '[') {
                $depth++;
            } elseif ($token === ']') {
                $depth--;
            }
        }
    }
    
    return $keys;
}

function strip_quotes($str) {
    return substr($str, 1, -1);
}

foreach (['en', 'ar', 'ku'] as $lang) {
    $keys = get_top_level_keys_raw("lang/{$lang}/messages.php");
    echo "=== {$lang} ===\n";
    $counts = [];
    foreach ($keys as $k) {
        $name = $k['key'];
        $counts[$name][] = $k['line'];
    }
    
    $has_dupes = false;
    foreach ($counts as $name => $lines) {
        if (count($lines) > 1) {
            $has_dupes = true;
            echo "Duplicate top-level key: '{$name}' at lines: " . implode(', ', $lines) . "\n";
        }
    }
    if (!$has_dupes) {
        echo "No duplicate top-level keys found!\n";
    }
    echo "\n";
}
