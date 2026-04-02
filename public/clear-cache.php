<?php
// DELETE THIS FILE AFTER USE
if (($_GET['token'] ?? '') !== 'desi2026secure') die('Unauthorized');

$basePath = dirname(__DIR__);
require $basePath . '/vendor/autoload.php';
$app = require $basePath . '/bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    \Illuminate\Support\Facades\Artisan::call('config:clear');
    echo "Config cleared<br>";
    \Illuminate\Support\Facades\Artisan::call('route:clear');
    echo "Routes cleared<br>";
    \Illuminate\Support\Facades\Artisan::call('view:clear');
    echo "Views cleared<br>";
    \Illuminate\Support\Facades\Artisan::call('cache:clear');
    echo "Cache cleared<br>";
    echo "<br><b style='color:green'>ALL CACHES CLEARED!</b><br>";
    echo "<br><a href='/dashboard'>Go to Dashboard</a>";
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage();
}
