<?php
/**
 * Properties By Desi - Server Setup Script
 * DELETE THIS FILE AFTER SETUP IS COMPLETE
 */

// Prevent unauthorized access - change this token before deploying
$setupToken = 'desi2026secure';

if (!isset($_GET['token']) || $_GET['token'] !== $setupToken) {
    die('Unauthorized. Use ?token=YOUR_TOKEN');
}

$basePath = dirname(__DIR__);
$step = $_GET['step'] ?? 'check';
$output = [];

function run($command, $basePath) {
    $fullCommand = "cd {$basePath} && {$command} 2>&1";
    exec($fullCommand, $out, $code);
    return ['output' => implode("\n", $out), 'code' => $code];
}

echo "<html><head><title>PBD Setup</title><style>body{font-family:monospace;padding:20px;max-width:800px;margin:0 auto}pre{background:#1e1e1e;color:#0f0;padding:15px;border-radius:8px;overflow-x:auto}.btn{display:inline-block;padding:10px 20px;background:#4f46e5;color:#fff;text-decoration:none;border-radius:6px;margin:5px}.btn:hover{background:#4338ca}.success{color:#22c55e}.error{color:#ef4444}h1{color:#4f46e5}</style></head><body>";
echo "<h1>Properties By Desi - Setup</h1>";

if ($step === 'check') {
    echo "<h2>System Check</h2>";
    echo "<p>PHP Version: <strong>" . phpversion() . "</strong> " . (version_compare(phpversion(), '8.2.0', '>=') ? '<span class="success">OK</span>' : '<span class="error">NEED 8.2+</span>') . "</p>";
    echo "<p>Base Path: <strong>{$basePath}</strong></p>";
    echo "<p>.env exists: <strong>" . (file_exists($basePath . '/.env') ? '<span class="success">YES</span>' : '<span class="error">NO</span>') . "</strong></p>";
    echo "<p>vendor/ exists: <strong>" . (is_dir($basePath . '/vendor') ? '<span class="success">YES</span>' : '<span class="error">NO - run composer install</span>') . "</strong></p>";
    echo "<p>storage/ writable: <strong>" . (is_writable($basePath . '/storage') ? '<span class="success">YES</span>' : '<span class="error">NO - fix permissions</span>') . "</strong></p>";

    echo "<br><a class='btn' href='?token={$setupToken}&step=env'>Step 1: Create .env</a> ";
    echo "<a class='btn' href='?token={$setupToken}&step=key'>Step 2: Generate Key</a> ";
    echo "<a class='btn' href='?token={$setupToken}&step=migrate'>Step 3: Migrate</a> ";
    echo "<a class='btn' href='?token={$setupToken}&step=seed'>Step 4: Seed Data</a> ";
    echo "<a class='btn' href='?token={$setupToken}&step=storage'>Step 5: Storage Link</a> ";
    echo "<a class='btn' href='?token={$setupToken}&step=permissions'>Step 6: Fix Permissions</a> ";
    echo "<a class='btn' href='?token={$setupToken}&step=cache'>Step 7: Cache Config</a> ";

} elseif ($step === 'env') {
    $env = "APP_NAME=\"Properties By Desi\"
APP_ENV=production
APP_KEY=
APP_DEBUG=false
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
DB_PASSWORD=#iOcTyR4

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
MAIL_FROM_ADDRESS=noreply@haztech.cloud
MAIL_FROM_NAME=\"Properties By Desi\"

VITE_APP_NAME=\"Properties By Desi\"
";
    file_put_contents($basePath . '/.env', $env);
    echo "<h2 class='success'>Step 1: .env created!</h2>";
    echo "<pre>" . htmlspecialchars($env) . "</pre>";
    echo "<a class='btn' href='?token={$setupToken}&step=key'>Next: Generate Key</a>";

} elseif ($step === 'key') {
    $result = run('php artisan key:generate --force', $basePath);
    echo "<h2>Step 2: Generate App Key</h2>";
    echo "<pre>{$result['output']}</pre>";
    echo "<p>" . ($result['code'] === 0 ? '<span class="success">SUCCESS</span>' : '<span class="error">FAILED</span>') . "</p>";
    echo "<a class='btn' href='?token={$setupToken}&step=migrate'>Next: Migrate</a>";

} elseif ($step === 'migrate') {
    $result = run('php artisan migrate --force', $basePath);
    echo "<h2>Step 3: Run Migrations</h2>";
    echo "<pre>{$result['output']}</pre>";
    echo "<p>" . ($result['code'] === 0 ? '<span class="success">SUCCESS</span>' : '<span class="error">FAILED</span>') . "</p>";
    echo "<a class='btn' href='?token={$setupToken}&step=seed'>Next: Seed Data</a>";

} elseif ($step === 'seed') {
    $result = run('php artisan db:seed --force', $basePath);
    echo "<h2>Step 4: Seed Database</h2>";
    echo "<pre>{$result['output']}</pre>";
    echo "<p>" . ($result['code'] === 0 ? '<span class="success">SUCCESS</span>' : '<span class="error">FAILED</span>') . "</p>";

    // Create custom users
    $result2 = run('php artisan tinker --execute="
        use App\\Models\\User;
        use Illuminate\\Support\\Facades\\Hash;
        use Spatie\\Permission\\Models\\Role;
        Role::firstOrCreate([\"name\" => \"super_admin\", \"guard_name\" => \"web\"]);
        User::where(\"email\", \"admin@propertiesbydesi.com\")->update([\"username\" => \"admin\"]);
        User::where(\"email\", \"rahul@propertiesbydesi.com\")->update([\"username\" => \"rahul\"]);
        User::where(\"email\", \"priya@propertiesbydesi.com\")->update([\"name\" => \"Pasad\", \"username\" => \"pasad\"]);
        User::where(\"email\", \"amit@propertiesbydesi.com\")->update([\"username\" => \"amit\"]);
        User::where(\"email\", \"sneha@propertiesbydesi.com\")->update([\"username\" => \"sneha\"]);
        \$f = User::create([\"name\" => \"Fiza\", \"username\" => \"fiza\", \"email\" => \"fiza@propertiesbydesi.com\", \"password\" => Hash::make(\"andupandu\")]);
        \$f->assignRole(\"admin\");
        \$m = User::create([\"name\" => \"Mohsin\", \"username\" => \"mohsin\", \"email\" => \"mohsin@propertiesbydesi.com\", \"password\" => Hash::make(\"andupandu\")]);
        \$m->assignRole(\"super_admin\");
        \$u = User::create([\"name\" => \"Mufeez\", \"username\" => \"mufeez\", \"email\" => \"mufeez@propertiesbydesi.com\", \"password\" => Hash::make(\"andupandu\")]);
        \$u->assignRole(\"super_admin\");
        echo \"Users created\";
    "', $basePath);
    echo "<pre>{$result2['output']}</pre>";
    echo "<a class='btn' href='?token={$setupToken}&step=storage'>Next: Storage Link</a>";

} elseif ($step === 'storage') {
    $result = run('php artisan storage:link --force', $basePath);
    echo "<h2>Step 5: Storage Link</h2>";
    echo "<pre>{$result['output']}</pre>";
    echo "<a class='btn' href='?token={$setupToken}&step=permissions'>Next: Fix Permissions</a>";

} elseif ($step === 'permissions') {
    run('chmod -R 775 storage bootstrap/cache', $basePath);
    echo "<h2 class='success'>Step 6: Permissions fixed!</h2>";
    echo "<a class='btn' href='?token={$setupToken}&step=cache'>Next: Cache Config</a>";

} elseif ($step === 'cache') {
    $r1 = run('php artisan config:cache', $basePath);
    $r2 = run('php artisan route:cache', $basePath);
    $r3 = run('php artisan view:cache', $basePath);
    echo "<h2>Step 7: Cache Everything</h2>";
    echo "<pre>{$r1['output']}\n{$r2['output']}\n{$r3['output']}</pre>";
    echo "<h2 class='success'>SETUP COMPLETE!</h2>";
    echo "<p><strong>IMPORTANT: Delete this file now!</strong> Go to File Manager and delete <code>public/setup.php</code></p>";
    echo "<a class='btn' href='/'>Visit Site</a>";
}

echo "</body></html>";
