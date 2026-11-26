<?php

declare(strict_types=1);

/**
 * اجرای مهاجرت‌های دیتابیس — CLI.
 *
 * اجرا: php scripts/migrate.php
 * همه فایل‌های migrations/*.sql را به ترتیب اعمال می‌کند. Idempotent:
 * migrationهای قبلی در جدول migrations ثبت شده و دوباره اجرا نمی‌شوند.
 *
 * @package Coop\Scripts
 * @version 0.1.0
 * @security-level data
 */

use App\Core\App;

require_once dirname(__DIR__) . '/src/Core/App.php';

$c = App::boot();
$db = $c->get(Database::class);
$pdo = $db->pdo();

if ($db->isSqlite()) {
    $db->execute('PRAGMA foreign_keys = ON');
}

$migrator = new Migrator($pdo, dirname(__DIR__) . '/migrations');
$applied = $migrator->migrate();

if ($applied === []) {
    echo "migrate: چیزی برای اعمال نیست — اسکیما به‌روز است.\n";
} else {
    foreach ($applied as $name) {
        echo "migrate: اعمال شد — {$name}\n";
    }
}
exit(0);
