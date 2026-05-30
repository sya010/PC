<?php
// Add compatibility and reset translations

// --- EN ---
$en = file_get_contents('lang/en/messages.php');
$en_new = <<<'BLOCK'

    // Compatibility
    'compatibility' => [
        'compatible' => 'Compatible',
        'incompatible' => 'Incompatible',
        'excellent' => 'Excellent',
        'good' => 'Good',
        'acceptable' => 'Acceptable',
        'not_ideal' => 'Not Ideal',
        'issue' => 'Compatibility Issue',
        'reset_group' => 'Reset',
    ],

    // Delivery
    'delivery' => [
        'title' => 'Delivery Information',
        'subtitle' => 'Where should we send your order?',
        'full_name' => 'Full Name',
        'email' => 'Email Address',
        'phone' => 'Phone Number',
        'city' => 'City',
        'state' => 'State / Province',
        'zip' => 'ZIP / Postal Code',
        'street' => 'Street Address',
        'continue' => 'Continue to Payment',
    ],
BLOCK;
$en = str_replace("    // Auth\n", $en_new . "\n\n    // Auth\n", $en);
file_put_contents('lang/en/messages.php', $en);

// --- KU ---
$ku = file_get_contents('lang/ku/messages.php');
$ku_new = <<<'BLOCK'

    // Compatibility
    'compatibility' => [
        'compatible' => 'گونجاوە',
        'incompatible' => 'ناگونجاوە',
        'excellent' => 'نایاب',
        'good' => 'باش',
        'acceptable' => 'قبوڵکراو',
        'not_ideal' => 'باش نییە',
        'issue' => 'کێشەی گونجاوی',
        'reset_group' => 'ڕیسێت',
    ],

    // Delivery
    'delivery' => [
        'title' => 'زانیاری گەیاندن',
        'subtitle' => 'داواکارییەکەت بۆ کوێ بنێرین؟',
        'full_name' => 'ناوی تەواو',
        'email' => 'ناونیشانی ئیمەیڵ',
        'phone' => 'ژمارەی مۆبایل',
        'city' => 'شار',
        'state' => 'پارێزگا',
        'zip' => 'کۆدی پۆستە',
        'street' => 'ناونیشانی شەقام',
        'continue' => 'بەردەوامبوون بۆ پارەدان',
    ],
BLOCK;
// Try with \r\n first
if (strpos($ku, "    // Auth\r\n") !== false) {
    $ku = str_replace("    // Auth\r\n", $ku_new . "\r\n\r\n    // Auth\r\n", $ku);
} else {
    $ku = str_replace("    // Auth\n", $ku_new . "\n\n    // Auth\n", $ku);
}
file_put_contents('lang/ku/messages.php', $ku);

// --- AR ---
$ar = file_get_contents('lang/ar/messages.php');
$ar_new = <<<'BLOCK'

    // Compatibility
    'compatibility' => [
        'compatible' => 'متوافق',
        'incompatible' => 'غير متوافق',
        'excellent' => 'ممتاز',
        'good' => 'جيد',
        'acceptable' => 'مقبول',
        'not_ideal' => 'غير مثالي',
        'issue' => 'مشكلة توافق',
        'reset_group' => 'إعادة تعيين',
    ],

    // Delivery
    'delivery' => [
        'title' => 'معلومات التوصيل',
        'subtitle' => 'أين يجب أن نرسل طلبك؟',
        'full_name' => 'الاسم الكامل',
        'email' => 'عنوان البريد الإلكتروني',
        'phone' => 'رقم الهاتف',
        'city' => 'المدينة',
        'state' => 'المحافظة',
        'zip' => 'الرمز البريدي',
        'street' => 'عنوان الشارع',
        'continue' => 'المتابعة للدفع',
    ],
BLOCK;
if (strpos($ar, "    // Admin Panel\r\n") !== false) {
    $ar = str_replace("    // Admin Panel\r\n", $ar_new . "\r\n\r\n    // Admin Panel\r\n", $ar);
} else {
    $ar = str_replace("    // Admin Panel\n", $ar_new . "\n\n    // Admin Panel\n", $ar);
}
file_put_contents('lang/ar/messages.php', $ar);

exec('php -l lang/en/messages.php 2>&1', $o1); echo "EN: " . end($o1) . "\n";
exec('php -l lang/ku/messages.php 2>&1', $o2); echo "KU: " . end($o2) . "\n";
exec('php -l lang/ar/messages.php 2>&1', $o3); echo "AR: " . end($o3) . "\n";
