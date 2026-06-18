<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$credentials = [
    'email' => 'superadmin@rescuemind.th',
    'password' => 'rescuemind@2024'
];

$result = Illuminate\Support\Facades\Auth::attempt($credentials);
echo 'Login result: ' . ($result ? 'SUCCESS' : 'FAILED') . "\n";
