<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$files = Illuminate\Support\Facades\File::allFiles(resource_path('views'));
foreach ($files as $file) {
    $content = file_get_contents($file);
    if (preg_match("/hasAnyRole\(\[(.*?)\]\)/", $content, $matches)) {
        if (!str_contains($matches[1], "'volunteer'")) {
            $newContent = preg_replace("/hasAnyRole\(\[(.*?)\]\)/", "hasAnyRole([$1, 'volunteer'])", $content);
            file_put_contents($file, $newContent);
            echo "Updated: " . $file->getPathname() . "\n";
        }
    }
}
echo "Done.\n";
