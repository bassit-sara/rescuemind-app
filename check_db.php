<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "DB Connection: " . config('database.default') . "\n";
$users = App\Models\User::all();
echo "Total users: " . $users->count() . "\n";
foreach($users as $user) {
    echo "ID: " . $user->id . " | Email: " . $user->email . " | PasswordHash: " . substr($user->password, 0, 15) . "... | Active: " . $user->is_active . "\n";
}
