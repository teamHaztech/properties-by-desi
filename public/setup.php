<?php
/**
 * Properties By Desi - Server Setup Script
 * DELETE THIS FILE AFTER SETUP IS COMPLETE
 */

$setupToken = 'desi2026secure';

if (!isset($_GET['token']) || $_GET['token'] !== $setupToken) {
    die('Unauthorized. Use ?token=YOUR_TOKEN');
}

$basePath = dirname(__DIR__);
$step = $_GET['step'] ?? 'check';

echo "<html><head><title>PBD Setup</title><style>body{font-family:monospace;padding:20px;max-width:800px;margin:0 auto}pre{background:#1e1e1e;color:#0f0;padding:15px;border-radius:8px;overflow-x:auto;white-space:pre-wrap}.btn{display:inline-block;padding:10px 20px;background:#4f46e5;color:#fff;text-decoration:none;border-radius:6px;margin:5px}.btn:hover{background:#4338ca}.success{color:#22c55e}.error{color:#ef4444}h1{color:#4f46e5}</style></head><body>";
echo "<h1>Properties By Desi - Setup</h1>";

if ($step === 'check') {
    echo "<h2>System Check</h2>";
    echo "<p>PHP Version: <strong>" . phpversion() . "</strong> " . (version_compare(phpversion(), '8.2.0', '>=') ? '<span class="success">OK</span>' : '<span class="error">NEED 8.2+</span>') . "</p>";
    echo "<p>Base Path: <strong>{$basePath}</strong></p>";
    echo "<p>.env exists: <strong>" . (file_exists($basePath . '/.env') ? '<span class="success">YES</span>' : '<span class="error">NO</span>') . "</strong></p>";
    echo "<p>vendor/ exists: <strong>" . (is_dir($basePath . '/vendor') ? '<span class="success">YES</span>' : '<span class="error">NO</span>') . "</strong></p>";

    $disabled = explode(',', ini_get('disable_functions'));
    $execDisabled = in_array('exec', array_map('trim', $disabled));
    echo "<p>exec() available: <strong>" . ($execDisabled ? '<span class="error">DISABLED</span>' : '<span class="success">YES</span>') . "</strong></p>";

    echo "<p>storage/ writable: <strong>" . (is_writable($basePath . '/storage') ? '<span class="success">YES</span>' : '<span class="error">NO</span>') . "</strong></p>";
    echo "<p>bootstrap/cache/ writable: <strong>" . (is_writable($basePath . '/bootstrap/cache') ? '<span class="success">YES</span>' : '<span class="error">NO</span>') . "</strong></p>";

    // Test DB connection
    $envFile = $basePath . '/.env';
    if (file_exists($envFile)) {
        $envContent = file_get_contents($envFile);
        preg_match('/DB_HOST=(.*)/', $envContent, $host);
        preg_match('/DB_DATABASE=(.*)/', $envContent, $db);
        preg_match('/DB_USERNAME=(.*)/', $envContent, $user);
        $dbHost = trim($host[1] ?? 'unknown');
        $dbName = trim($db[1] ?? 'unknown');
        $dbUser = trim($user[1] ?? 'unknown');
        echo "<p>DB Config: <strong>{$dbUser}@{$dbHost}/{$dbName}</strong></p>";
    }

    echo "<br>";
    echo "<a class='btn' href='?token={$setupToken}&step=env'>Step 1: Create .env</a> ";
    echo "<a class='btn' href='?token={$setupToken}&step=artisan&cmd=key:generate --force'>Step 2: Generate Key</a> ";
    echo "<a class='btn' href='?token={$setupToken}&step=artisan&cmd=migrate --force'>Step 3: Migrate</a> ";
    echo "<a class='btn' href='?token={$setupToken}&step=artisan&cmd=db:seed --force'>Step 4: Seed</a> ";
    echo "<a class='btn' href='?token={$setupToken}&step=users'>Step 5: Create Users</a> ";
    echo "<a class='btn' href='?token={$setupToken}&step=artisan&cmd=storage:link --force'>Step 6: Storage Link</a> ";
    echo "<a class='btn' href='?token={$setupToken}&step=permissions'>Step 7: Fix Permissions</a> ";
    echo "<a class='btn' href='?token={$setupToken}&step=optimize'>Step 8: Optimize</a> ";

} elseif ($step === 'env') {
    $env = 'APP_NAME="Properties By Desi"
APP_ENV=production
APP_KEY=
APP_DEBUG=true
APP_URL=https://haztech.cloud

APP_LOCALE=en
APP_FALLBACK_LOCALE=en
APP_FAKER_LOCALE=en_US
APP_MAINTENANCE_DRIVER=file

BCRYPT_ROUNDS=12

LOG_CHANNEL=stack
LOG_STACK=single
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=error

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=desi
DB_USERNAME=desiuser
DB_PASSWORD="#iOcTyR4"

SESSION_DRIVER=file
SESSION_LIFETIME=120
SESSION_ENCRYPT=false
SESSION_PATH=/
SESSION_DOMAIN=null

BROADCAST_CONNECTION=log
FILESYSTEM_DISK=local
QUEUE_CONNECTION=sync
CACHE_STORE=file

MAIL_MAILER=log
MAIL_FROM_ADDRESS="noreply@haztech.cloud"
MAIL_FROM_NAME="Properties By Desi"

VITE_APP_NAME="Properties By Desi"
';
    file_put_contents($basePath . '/.env', $env);
    echo "<h2 class='success'>Step 1: .env created!</h2>";
    echo "<pre>" . htmlspecialchars($env) . "</pre>";
    echo "<a class='btn' href='?token={$setupToken}&step=artisan&cmd=key:generate --force'>Next: Generate Key</a>";

} elseif ($step === 'artisan') {
    $cmd = $_GET['cmd'] ?? '';
    $allowed = ['key:generate --force', 'migrate --force', 'db:seed --force', 'storage:link --force', 'config:cache', 'route:cache', 'view:cache', 'config:clear', 'cache:clear'];

    if (!in_array($cmd, $allowed)) {
        echo "<p class='error'>Command not allowed: " . htmlspecialchars($cmd) . "</p>";
    } else {
        // Try to run via artisan
        $artisan = $basePath . '/artisan';
        $fullCmd = "cd {$basePath} && php {$artisan} {$cmd} 2>&1";
        $output = shell_exec($fullCmd);

        if ($output === null) {
            // shell_exec disabled, try exec
            exec($fullCmd, $outLines, $code);
            $output = implode("\n", $outLines);
        }

        if ($output === null || $output === '') {
            // Both disabled, try passthru with output buffering
            ob_start();
            passthru($fullCmd);
            $output = ob_get_clean();
        }

        echo "<h2>Running: php artisan {$cmd}</h2>";
        echo "<pre>" . htmlspecialchars($output ?: 'No output (command may have run successfully)') . "</pre>";
    }
    echo "<br><a class='btn' href='?token={$setupToken}&step=check'>Back to Steps</a>";

} elseif ($step === 'users') {
    // Bootstrap Laravel
    require $basePath . '/vendor/autoload.php';
    $app = require_once $basePath . '/bootstrap/app.php';
    $kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
    $kernel->bootstrap();

    try {
        $roleClass = \Spatie\Permission\Models\Role::class;
        $roleClass::firstOrCreate(['name' => 'super_admin', 'guard_name' => 'web']);

        $userModel = \App\Models\User::class;

        // Update seeded users with usernames
        $userModel::where('email', 'admin@propertiesbydesi.com')->update(['username' => 'admin']);
        $userModel::where('email', 'rahul@propertiesbydesi.com')->update(['username' => 'rahul']);
        $userModel::where('email', 'priya@propertiesbydesi.com')->update(['name' => 'Pasad', 'username' => 'pasad']);
        $userModel::where('email', 'amit@propertiesbydesi.com')->update(['username' => 'amit']);
        $userModel::where('email', 'sneha@propertiesbydesi.com')->update(['username' => 'sneha']);

        // Create Fiza
        if (!$userModel::where('username', 'fiza')->exists()) {
            $f = $userModel::create(['name' => 'Fiza', 'username' => 'fiza', 'email' => 'fiza@propertiesbydesi.com', 'password' => \Illuminate\Support\Facades\Hash::make('andupandu')]);
            $f->assignRole('admin');
            echo "<p class='success'>Fiza created (admin)</p>";
        }

        // Create Mohsin
        if (!$userModel::where('username', 'mohsin')->exists()) {
            $m = $userModel::create(['name' => 'Mohsin', 'username' => 'mohsin', 'email' => 'mohsin@propertiesbydesi.com', 'password' => \Illuminate\Support\Facades\Hash::make('andupandu')]);
            $m->assignRole('super_admin');
            echo "<p class='success'>Mohsin created (super_admin)</p>";
        }

        // Create Mufeez
        if (!$userModel::where('username', 'mufeez')->exists()) {
            $u = $userModel::create(['name' => 'Mufeez', 'username' => 'mufeez', 'email' => 'mufeez@propertiesbydesi.com', 'password' => \Illuminate\Support\Facades\Hash::make('andupandu')]);
            $u->assignRole('super_admin');
            echo "<p class='success'>Mufeez created (super_admin)</p>";
        }

        // List all users
        echo "<h3>All Users:</h3><pre>";
        foreach ($userModel::all() as $u) {
            echo "{$u->id}. {$u->name} ({$u->username}) - Roles: {$u->getRoleNames()->implode(', ')}\n";
        }
        echo "</pre>";
        echo "<h2 class='success'>Users created!</h2>";
    } catch (\Exception $e) {
        echo "<p class='error'>Error: " . htmlspecialchars($e->getMessage()) . "</p>";
        echo "<pre>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
    }
    echo "<a class='btn' href='?token={$setupToken}&step=check'>Back to Steps</a>";

} elseif ($step === 'permissions') {
    $dirs = ['storage', 'storage/app', 'storage/framework', 'storage/framework/cache', 'storage/framework/sessions', 'storage/framework/views', 'storage/logs', 'bootstrap/cache'];
    foreach ($dirs as $dir) {
        $path = $basePath . '/' . $dir;
        if (is_dir($path)) {
            chmod($path, 0775);
            echo "<p class='success'>chmod 775: {$dir}</p>";
        } else {
            @mkdir($path, 0775, true);
            echo "<p class='success'>created + chmod 775: {$dir}</p>";
        }
    }
    echo "<h2 class='success'>Permissions fixed!</h2>";
    echo "<a class='btn' href='?token={$setupToken}&step=check'>Back to Steps</a>";

} elseif ($step === 'optimize') {
    require $basePath . '/vendor/autoload.php';
    $app = require_once $basePath . '/bootstrap/app.php';
    $kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
    $kernel->bootstrap();

    try {
        \Illuminate\Support\Facades\Artisan::call('config:cache');
        echo "<p class='success'>Config cached</p>";
        \Illuminate\Support\Facades\Artisan::call('route:cache');
        echo "<p class='success'>Routes cached</p>";
        \Illuminate\Support\Facades\Artisan::call('view:cache');
        echo "<p class='success'>Views cached</p>";
    } catch (\Exception $e) {
        echo "<p class='error'>Error: " . htmlspecialchars($e->getMessage()) . "</p>";
    }

    echo "<h2 class='success'>SETUP COMPLETE!</h2>";
    echo "<p><strong style='color:red'>IMPORTANT: Delete this file now!</strong><br>File Manager > public_html/public/setup.php > Delete</p>";
    echo "<a class='btn' href='/'>Visit Site</a>";
}

echo "</body></html>";
