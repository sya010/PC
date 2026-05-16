<?php
$files = [
    'en' => [
        'reset_all_title' => 'Reset Entire Build?',
        'reset_core_title' => 'Reset Core System?',
        'reset_peri_title' => 'Reset Peripherals?',
        'reset_all_desc' => 'This will clear all your selected components and quantities. This action cannot be undone.',
        'reset_core_desc' => 'This will clear all core PC parts like the CPU, GPU, and Motherboard. This action cannot be undone.',
        'reset_peri_desc' => 'This will clear all selected peripherals and accessories. This action cannot be undone.',
        'cancel' => 'Cancel',
        'yes_reset' => 'Yes, Reset',
    ],
    'ku' => [
        'reset_all_title' => 'تەواوی دروستکراوەکە ڕیسێت دەکەیت؟',
        'reset_core_title' => 'سیستەمی سەرەکی ڕیسێت دەکەیت؟',
        'reset_peri_title' => 'ئامێرەکانی دەوروبەر ڕیسێت دەکەیت؟',
        'reset_all_desc' => 'ئەمە هەموو پارچە و بڕە هەڵبژێردراوەکانت دەسڕێتەوە. ئەم کردارە ناتوانرێت بگەڕێندرێتەوە.',
        'reset_core_desc' => 'ئەمە هەموو پارچە سەرەکییەکانی وەک پرۆسێسەر، کارتی گرافیک، و مادەربۆرد دەسڕێتەوە. ئەم کردارە ناتوانرێت بگەڕێندرێتەوە.',
        'reset_peri_desc' => 'ئەمە هەموو ئامێرەکانی دەوروبەر و پێداویستییەکان دەسڕێتەوە. ئەم کردارە ناتوانرێت بگەڕێندرێتەوە.',
        'cancel' => 'پاشگەزبوونەوە',
        'yes_reset' => 'بەڵێ، ڕیسێت',
    ],
    'ar' => [
        'reset_all_title' => 'هل تريد إعادة تعيين التجميعة بالكامل؟',
        'reset_core_title' => 'هل تريد إعادة تعيين النظام الأساسي؟',
        'reset_peri_title' => 'هل تريد إعادة تعيين الملحقات؟',
        'reset_all_desc' => 'سيؤدي هذا إلى مسح جميع المكونات والكميات المحددة. لا يمكن التراجع عن هذا الإجراء.',
        'reset_core_desc' => 'سيؤدي هذا إلى مسح جميع قطع الكمبيوتر الأساسية مثل المعالج وبطاقة الرسوميات واللوحة الأم. لا يمكن التراجع عن هذا الإجراء.',
        'reset_peri_desc' => 'سيؤدي هذا إلى مسح جميع الملحقات والإكسسوارات المحددة. لا يمكن التراجع عن هذا الإجراء.',
        'cancel' => 'إلغاء',
        'yes_reset' => 'نعم، إعادة تعيين',
    ]
];

foreach ($files as $lang => $trans) {
    $path = "lang/{$lang}/messages.php";
    $content = file_get_contents($path);
    
    // Build the replacement block
    $block = "";
    foreach ($trans as $key => $val) {
        $block .= "        '{$key}' => '{$val}',\n";
    }
    
    // Inject at the end of pc_builder block
    $replacement = $block . "    ],\n";
    $content = preg_replace("/    \],\n\n    \/\/ Shop/", $replacement . "\n    // Shop", $content);
    
    file_put_contents($path, $content);
}

exec('php -l lang/en/messages.php 2>&1', $o1); echo "EN: " . end($o1) . "\n";
exec('php -l lang/ku/messages.php 2>&1', $o2); echo "KU: " . end($o2) . "\n";
exec('php -l lang/ar/messages.php 2>&1', $o3); echo "AR: " . end($o3) . "\n";
