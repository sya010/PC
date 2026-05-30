<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

$email = 'syako@gmail.com';
$password = 'syako123';

$user = User::where('email', $email)->first();

if ($user) {
    $user->update([
        'password' => Hash::make($password),
        'is_admin' => true,
    ]);
    echo "User already exists. Updated password and set as admin.\n";
} else {
    User::create([
        'name' => 'Syako',
        'email' => $email,
        'password' => Hash::make($password),
        'is_admin' => true,
    ]);
    echo "Admin user created successfully.\n";
}

