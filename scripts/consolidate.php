<?php

// Consolidate lang files to ensure exact key parity and order, removing raw duplicates.

function get_flat_array($arr, $prefix = '') {
    $flat = [];
    foreach ($arr as $k => $v) {
        $path = $prefix === '' ? $k : "$prefix.$k";
        if (is_array($v)) {
            $flat = array_merge($flat, get_flat_array($v, $path));
        } else {
            $flat[$path] = $v;
        }
    }
    return $flat;
}

$en = require 'lang/en/messages.php';
$ar = require 'lang/ar/messages.php';
$ku = require 'lang/ku/messages.php';

$en_flat = get_flat_array($en);
$ar_flat = get_flat_array($ar);
$ku_flat = get_flat_array($ku);

// Get the union of all keys across all three languages
$all_keys = array_unique(array_merge(
    array_keys($en_flat),
    array_keys($ar_flat),
    array_keys($ku_flat)
));

// We want to sort the keys to maintain a clean structure.
// Let's use EN's key order as the primary guide, and append any new keys found only in AR/KU at the end of their respective groups or at the end.
// To do this simply, we sort all_keys alphabetically or by hierarchical prefix.
sort($all_keys);

$new_en = [];
$new_ar = [];
$new_ku = [];

foreach ($all_keys as $key) {
    // Determine translation values
    $en_val = isset($en_flat[$key]) ? $en_flat[$key] : '';
    $ar_val = isset($ar_flat[$key]) ? $ar_flat[$key] : '';
    $ku_val = isset($ku_flat[$key]) ? $ku_flat[$key] : '';
    
    // If a translation is completely missing in AR or KU, fall back to EN
    if ($ar_val === '' && $en_val !== '') $ar_val = $en_val;
    if ($ku_val === '' && $en_val !== '') $ku_val = $en_val;
    if ($en_val === '' && $ar_val !== '') $en_val = $ar_val;
    if ($en_val === '' && $ku_val !== '') $en_val = $ku_val;
    if ($ar_val === '' && $ku_val !== '') $ar_val = $ku_val;
    if ($ku_val === '' && $ar_val !== '') $ku_val = $ar_val;

    $new_en[$key] = $en_val;
    $new_ar[$key] = $ar_val;
    $new_ku[$key] = $ku_val;
}

// Convert flat dotted arrays back to nested arrays
function inflate_array($flat) {
    $nested = [];
    foreach ($flat as $key => $value) {
        $parts = explode('.', $key);
        $temp = &$nested;
        foreach ($parts as $part) {
            if (!isset($temp[$part])) {
                $temp[$part] = [];
            }
            $temp = &$temp[$part];
        }
        $temp = $value;
    }
    return $nested;
}

$en_nested = inflate_array($new_en);
$ar_nested = inflate_array($new_ar);
$ku_nested = inflate_array($new_ku);

// Helper function to format PHP arrays beautifully
function export_array_pretty($array, $indent = 2) {
    $spaces = str_repeat(' ', $indent);
    $output = "[\n";
    foreach ($array as $key => $value) {
        $key_esc = addcslashes($key, "'\\");
        if (is_array($value)) {
            $output .= "{$spaces}'{$key_esc}' => \n" . str_repeat(' ', $indent) . export_array_pretty($value, $indent + 2) . ",\n";
        } else {
            $val_esc = addcslashes($value, "'\\");
            // If the value contains single quotes, let's escape it properly
            $output .= "{$spaces}'{$key_esc}' => '{$val_esc}',\n";
        }
    }
    $output .= str_repeat(' ', $indent - 2) . "]";
    return $output;
}

// Write the files back
file_put_contents('lang/en/messages.php', "<?php\n\nreturn " . export_array_pretty($en_nested) . ";\n");
file_put_contents('lang/ar/messages.php', "<?php\n\nreturn " . export_array_pretty($ar_nested) . ";\n");
file_put_contents('lang/ku/messages.php', "<?php\n\nreturn " . export_array_pretty($ku_nested) . ";\n");

echo "Consolidation complete!\n";
echo "Total keys: " . count($all_keys) . "\n";
