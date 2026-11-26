<?php

declare(strict_types=1);

/**
 * اجرای مهاجرت از مرورگر — برای هاست اشتراکی بدون SSH.
 *
 * محافظت با توکن یک‌بارمصرف از محیط (MIGRATE_TOKEN).
 * استفاده: https://example.com/migrate.php?token=<MIGRATE_TOKEN>[&seed=1]
 *
 * پس از نصب، این فایل یا توکن را حذف کنید.
 *
 * @package Coop\Entry
 * @version 0.1.0
 * @security-level data
 */

require_once dirname(__DIR__) . '/src/Core/App.php';

App\Core\App::boot();
$c = App\Core\App::$container;
$db = $c->get(Database::class);
$pdo = $db->pdo();

function out(string $msg): void
{
    echo htmlspecialchars($msg, ENT_QUOTES, 'UTF-8'), "<br>\n";
}

header('Content-Type: text/html; charset=UTF-8');
echo '<!DOCTYPE html><html lang="fa" dir="rtl"><head><meta charset="UTF-8"><title>مهاجرت</title></head><body style="font-family:sans-serif;direction:rtl;padding:24px">';

$token = getenv('MIGRATE_TOKEN');
$submitted = $_GET['token'] ?? '';
if ($token === false || $token === '' || !is_string($submitted) || !hash_equals($token, $submitted)) {
    http_response_code(403);
    out('۴۰۳ — توکن مهاجرت نامعتبر است. متغیر محیطی MIGRATE_TOKEN را تنظیم کنید.');
    echo '</body></html>';
    exit;
}

if ($db->isSqlite()) {
    $db->execute('PRAGMA foreign_keys = ON');
}

$migrator = new Migrator($pdo, dirname(__DIR__) . '/migrations');
$applied = $migrator->migrate();

if ($applied === []) {
    out('مهاجرت: اسکیما به‌روز است — چیزی اعمال نشد.');
} else {
    foreach ($applied as $name) {
        out('مهاجرت: اعمال شد — ' . $name);
    }
}

if (isset($_GET['seed'])) {
    out('اجرای داده‌های اولیه...');
    require dirname(__DIR__) . '/scripts/seed.php';
}

out('پایان. اکنون می‌توانید index.php را باز کنید.');
echo '</body></html>';
