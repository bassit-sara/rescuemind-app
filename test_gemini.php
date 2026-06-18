<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$payload = [
    'system_instruction' => ['parts' => [['text' => 'test']]],
    'contents' => [['role' => 'user', 'parts' => [['text' => 'hello']]]]
];
$key = config('services.gemini.key');
$url = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key=' . $key;
$response = Illuminate\Support\Facades\Http::post($url, $payload);
echo "STATUS: " . $response->status() . "\n";
echo "BODY: " . $response->body() . "\n";

$payload2 = [
    'systemInstruction' => ['parts' => [['text' => 'test']]],
    'contents' => [['role' => 'user', 'parts' => [['text' => 'hello']]]]
];
$response2 = Illuminate\Support\Facades\Http::post($url, $payload2);
echo "STATUS 2: " . $response2->status() . "\n";
echo "BODY 2: " . $response2->body() . "\n";
