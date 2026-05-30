<?php

$en = require 'lang/en/messages.php';
$ar = require 'lang/ar/messages.php';
$ku = require 'lang/ku/messages.php';

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

$en_flat = get_flat_array($en);
$ar_flat = get_flat_array($ar);
$ku_flat = get_flat_array($ku);

echo "Checking if AR values are equal to EN values (excluding matching names or numbers):\n";
$untranslated_ar = [];
foreach ($en_flat as $path => $en_val) {
    if (!isset($ar_flat[$path])) {
        echo "Missing in AR array at runtime: $path\n";
        continue;
    }
    $ar_val = $ar_flat[$path];
    if ($en_val === $ar_val && preg_match('/[a-zA-Z]/', $en_val)) {
        // Exclude things like brand names or acronyms that should be identical
        if (in_array($path, ['site_name', 'currency', 'languages.en', 'footer.email', 'profile.email_address', 'admin.sidebar.sign_out', 'auth.email'])) {
            continue;
        }
        if (strpos($path, 'categories.') === 0 || strpos($path, 'theme.') === 0) {
            continue; // themes and categories can sometimes be identical in english
        }
        $untranslated_ar[$path] = $en_val;
    }
}

echo "AR potential untranslated count: " . count($untranslated_ar) . "\n";
print_r($untranslated_ar);

echo "\nChecking if KU values are equal to EN values:\n";
$untranslated_ku = [];
foreach ($en_flat as $path => $en_val) {
    if (!isset($ku_flat[$path])) {
        echo "Missing in KU array at runtime: $path\n";
        continue;
    }
    $ku_val = $ku_flat[$path];
    if ($en_val === $ku_val && preg_match('/[a-zA-Z]/', $en_val)) {
        if (in_array($path, ['site_name', 'currency', 'languages.en', 'footer.email', 'profile.email_address', 'admin.sidebar.sign_out', 'auth.email'])) {
            continue;
        }
        if (strpos($path, 'categories.') === 0 || strpos($path, 'theme.') === 0) {
            continue;
        }
        $untranslated_ku[$path] = $en_val;
    }
}

echo "KU potential untranslated count: " . count($untranslated_ku) . "\n";
print_r($untranslated_ku);
