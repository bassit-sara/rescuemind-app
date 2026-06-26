<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$bookings = App\Models\ShelterBooking::where('status', 'pending')->get();
foreach($bookings as $b) {
    $b->status = 'approved';
    $b->room_key = strtoupper(\Illuminate\Support\Str::random(6));
    $b->save();
}
echo "Approved " . $bookings->count() . " bookings.\n";
