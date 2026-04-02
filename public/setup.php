<?php
/**
 * Properties By Desi - Server Setup Script
 * DELETE THIS FILE AFTER SETUP IS COMPLETE
 */

$setupToken = 'desi2026secure';
if (!isset($_GET['token']) || $_GET['token'] !== $setupToken) {
    die('Unauthorized');
}

$basePath = dirname(__DIR__);
$step = $_GET['step'] ?? 'check';

echo "<html><head><title>PBD Setup</title><style>body{font-family:monospace;padding:20px;max-width:800px;margin:0 auto}pre{background:#1e1e1e;color:#0f0;padding:15px;border-radius:8px;overflow-x:auto;white-space:pre-wrap}.btn{display:inline-block;padding:10px 20px;background:#4f46e5;color:#fff;text-decoration:none;border-radius:6px;margin:5px}.btn:hover{background:#4338ca}.ok{color:#22c55e}.err{color:#ef4444}h1{color:#4f46e5}</style></head><body>";
echo "<h1>Properties By Desi - Setup</h1>";

// Helper: bootstrap Laravel
function bootLaravel($basePath) {
    require $basePath . '/vendor/autoload.php';
    $app = require $basePath . '/bootstrap/app.php';
    $kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
    $kernel->bootstrap();
    return $app;
}

if ($step === 'check') {
    echo "<h2>System Check</h2>";
    echo "<p>PHP: <b>" . phpversion() . "</b> " . (version_compare(phpversion(), '8.2.0', '>=') ? '<span class="ok">OK</span>' : '<span class="err">NEED 8.2+</span>') . "</p>";
    echo "<p>Path: <b>{$basePath}</b></p>";
    echo "<p>.env: <b>" . (file_exists($basePath . '/.env') ? '<span class="ok">YES</span>' : '<span class="err">NO</span>') . "</b></p>";
    echo "<p>.env.production: <b>" . (file_exists($basePath . '/.env.production') ? '<span class="ok">YES</span>' : '<span class="err">NO</span>') . "</b></p>";
    echo "<p>vendor/: <b>" . (file_exists($basePath . '/vendor/autoload.php') ? '<span class="ok">YES</span>' : '<span class="err">NO</span>') . "</b></p>";
    echo "<p>storage/ writable: <b>" . (is_writable($basePath . '/storage') ? '<span class="ok">YES</span>' : '<span class="err">NO</span>') . "</b></p>";

    echo "<br><h3>Run in order:</h3>";
    echo "<a class='btn' href='?token={$setupToken}&step=env'>1. Create .env</a> ";
    echo "<a class='btn' href='?token={$setupToken}&step=permissions'>2. Fix Permissions</a> ";
    echo "<a class='btn' href='?token={$setupToken}&step=key'>3. Generate Key</a> ";
    echo "<a class='btn' href='?token={$setupToken}&step=migrate'>4. Migrate DB</a> ";
    echo "<a class='btn' href='?token={$setupToken}&step=seed'>5. Seed Data</a> ";
    echo "<a class='btn' href='?token={$setupToken}&step=users'>6. Create Users</a> ";
    echo "<a class='btn' href='?token={$setupToken}&step=storage'>7. Storage Link</a> ";
    echo "<a class='btn' href='?token={$setupToken}&step=done'>8. Finalize</a> ";

} elseif ($step === 'env') {
    if (file_exists($basePath . '/.env.production')) {
        copy($basePath . '/.env.production', $basePath . '/.env');
        echo "<h2 class='ok'>Step 1: .env created from .env.production</h2>";
        echo "<pre>" . htmlspecialchars(file_get_contents($basePath . '/.env')) . "</pre>";
    } else {
        echo "<h2 class='err'>No .env.production found!</h2>";
    }
    echo "<a class='btn' href='?token={$setupToken}&step=permissions'>Next: Fix Permissions</a>";

} elseif ($step === 'permissions') {
    $dirs = ['storage', 'storage/app', 'storage/app/private', 'storage/app/public', 'storage/framework', 'storage/framework/cache', 'storage/framework/cache/data', 'storage/framework/sessions', 'storage/framework/testing', 'storage/framework/views', 'storage/logs', 'bootstrap/cache'];
    foreach ($dirs as $dir) {
        $path = $basePath . '/' . $dir;
        if (!is_dir($path)) @mkdir($path, 0775, true);
        @chmod($path, 0775);
        echo "<p class='ok'>OK: {$dir}</p>";
    }
    // Create .gitignore in storage/logs if missing
    @file_put_contents($basePath . '/storage/logs/.gitignore', "*\n!.gitignore\n");
    echo "<h2 class='ok'>Step 2: Permissions fixed!</h2>";
    echo "<a class='btn' href='?token={$setupToken}&step=key'>Next: Generate Key</a>";

} elseif ($step === 'key') {
    try {
        $app = bootLaravel($basePath);
        \Illuminate\Support\Facades\Artisan::call('key:generate', ['--force' => true]);
        echo "<h2 class='ok'>Step 3: App key generated!</h2>";
        echo "<pre>" . \Illuminate\Support\Facades\Artisan::output() . "</pre>";
    } catch (\Exception $e) {
        echo "<h2 class='err'>Error:</h2><pre>" . $e->getMessage() . "</pre>";
    }
    echo "<a class='btn' href='?token={$setupToken}&step=migrate'>Next: Migrate</a>";

} elseif ($step === 'migrate') {
    try {
        $app = bootLaravel($basePath);
        \Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
        echo "<h2 class='ok'>Step 4: Migrations complete!</h2>";
        echo "<pre>" . \Illuminate\Support\Facades\Artisan::output() . "</pre>";
    } catch (\Exception $e) {
        echo "<h2 class='err'>Error:</h2><pre>" . $e->getMessage() . "\n\n" . $e->getTraceAsString() . "</pre>";
    }
    echo "<a class='btn' href='?token={$setupToken}&step=seed'>Next: Seed</a>";

} elseif ($step === 'seed') {
    try {
        $app = bootLaravel($basePath);
        \Illuminate\Support\Facades\Artisan::call('db:seed', ['--force' => true]);
        echo "<h2 class='ok'>Step 5: Database seeded!</h2>";
        echo "<pre>" . \Illuminate\Support\Facades\Artisan::output() . "</pre>";
    } catch (\Exception $e) {
        echo "<h2 class='err'>Error:</h2><pre>" . $e->getMessage() . "</pre>";
    }
    echo "<a class='btn' href='?token={$setupToken}&step=users'>Next: Create Users</a>";

} elseif ($step === 'users') {
    try {
        $app = bootLaravel($basePath);

        \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'super_admin', 'guard_name' => 'web']);

        \App\Models\User::where('email', 'admin@propertiesbydesi.com')->update(['username' => 'admin']);
        \App\Models\User::where('email', 'rahul@propertiesbydesi.com')->update(['username' => 'rahul']);
        \App\Models\User::where('email', 'priya@propertiesbydesi.com')->update(['name' => 'Pasad', 'username' => 'pasad']);
        \App\Models\User::where('email', 'amit@propertiesbydesi.com')->update(['username' => 'amit']);
        \App\Models\User::where('email', 'sneha@propertiesbydesi.com')->update(['username' => 'sneha']);

        if (!\App\Models\User::where('username', 'fiza')->exists()) {
            $f = \App\Models\User::create(['name' => 'Fiza', 'username' => 'fiza', 'email' => 'fiza@propertiesbydesi.com', 'password' => \Illuminate\Support\Facades\Hash::make('andupandu')]);
            $f->assignRole('admin');
        }
        if (!\App\Models\User::where('username', 'mohsin')->exists()) {
            $m = \App\Models\User::create(['name' => 'Mohsin', 'username' => 'mohsin', 'email' => 'mohsin@propertiesbydesi.com', 'password' => \Illuminate\Support\Facades\Hash::make('andupandu')]);
            $m->assignRole('super_admin');
        }
        if (!\App\Models\User::where('username', 'mufeez')->exists()) {
            $u = \App\Models\User::create(['name' => 'Mufeez', 'username' => 'mufeez', 'email' => 'mufeez@propertiesbydesi.com', 'password' => \Illuminate\Support\Facades\Hash::make('andupandu')]);
            $u->assignRole('super_admin');
        }

        echo "<h2 class='ok'>Step 6: Users created!</h2><pre>";
        foreach (\App\Models\User::all() as $u) {
            echo "{$u->id}. {$u->name} (@{$u->username}) — " . $u->getRoleNames()->implode(', ') . "\n";
        }
        echo "</pre>";
    } catch (\Exception $e) {
        echo "<h2 class='err'>Error:</h2><pre>" . $e->getMessage() . "</pre>";
    }
    echo "<a class='btn' href='?token={$setupToken}&step=storage'>Next: Storage Link</a>";

} elseif ($step === 'storage') {
    try {
        $app = bootLaravel($basePath);
        \Illuminate\Support\Facades\Artisan::call('storage:link', ['--force' => true]);
        echo "<h2 class='ok'>Step 7: Storage linked!</h2>";
        echo "<pre>" . \Illuminate\Support\Facades\Artisan::output() . "</pre>";
    } catch (\Exception $e) {
        echo "<h2 class='err'>Error:</h2><pre>" . $e->getMessage() . "</pre>";
    }
    echo "<a class='btn' href='?token={$setupToken}&step=done'>Next: Finalize</a>";

} elseif ($step === 'done') {
    try {
        $app = bootLaravel($basePath);
        \Illuminate\Support\Facades\Artisan::call('config:cache');
        echo "<p class='ok'>Config cached</p>";
        \Illuminate\Support\Facades\Artisan::call('route:cache');
        echo "<p class='ok'>Routes cached</p>";
        \Illuminate\Support\Facades\Artisan::call('view:cache');
        echo "<p class='ok'>Views cached</p>";
    } catch (\Exception $e) {
        echo "<p class='err'>Cache error (non-critical): " . $e->getMessage() . "</p>";
    }

    echo "<h2 class='ok'>SETUP COMPLETE!</h2>";
    echo "<p style='color:red;font-size:18px'><b>DELETE this file now!</b><br>File Manager > public_html/public/setup.php > Delete</p>";
    echo "<a class='btn' href='/'>Open Site</a>";
}

echo "</body></html>";
