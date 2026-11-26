<?php
declare(strict_types=1);
/**
 * لایوت پنل مدیریت.
 * @package Coop\Templates
 * @version 0.1.0
 * @security-level admin
 */
$user = $__data['user'] ?? null;
$flash = $__data['flash'] ?? [];
$adminPrefix = $__data['adminPrefix'] ?? '/admin';
$roles = $__data['roles'] ?? [];
$pendingOrders = (int) ($__data['pendingOrders'] ?? 0);
$unreadMessages = (int) ($__data['unreadMessages'] ?? 0);
$title = $__data['title'] ?? 'پنل مدیریت';
$section = $__data['section'] ?? '';
$content = $__data['content'] ?? '';
$nav = [
    'dashboard'   => ['داشبورد', '/admin'],
    'orders'      => ['سفارش‌ها', '/admin/orders'],
    'products'    => ['محصولات', '/admin/products'],
    'categories'  => ['دسته‌بندی‌ها', '/admin/categories'],
    'members'     => ['نساجان', '/admin/members'],
    'customers'   => ['مشتریان', '/admin/customers'],
    'settlements' => ['تسویه مالی', '/admin/settlements'],
    'coupons'     => ['کدهای تخفیف', '/admin/coupons'],
    'reports'     => ['گزارش‌ها', '/admin/reports'],
    'messages'    => ['پیام‌ها', '/admin/messages'],
    'settings'    => ['تنظیمات', '/admin/settings'],
];
?>
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <title><?= View::e($title) ?> — پنل مدیریت</title>
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body class="admin-body">
<div class="admin-shell">
    <aside class="admin-sidebar">
        <div class="brand">🧶 پنل تعاونی</div>
        <ul class="admin-nav">
            <?php foreach ($nav as $key => $item): ?>
                <?php [$label, $url] = $item; ?>
                <li>
                    <a href="<?= $url ?>" class="<?= $section === $key ? 'active' : '' ?>">
                        <?= View::e($label) ?>
                        <?php if ($key === 'orders' && $pendingOrders > 0): ?>
                            <span class="badge"><?= $toFa((string) $pendingOrders) ?></span>
                        <?php elseif ($key === 'messages' && $unreadMessages > 0): ?>
                            <span class="badge"><?= $toFa((string) $unreadMessages) ?></span>
                        <?php endif; ?>
                    </a>
                </li>
            <?php endforeach; ?>
            <li><a href="/" target="_blank">مشاهده فروشگاه ↗</a></li>
            <li><a href="/logout">خروج</a></li>
        </ul>
    </aside>
    <div class="admin-main">
        <div class="admin-topbar">
            <div style="display:flex;align-items:center;gap:10px;">
                <button class="menu-toggle">☰ منو</button>
                <h1><?= View::e($title) ?></h1>
            </div>
            <div class="text-muted" style="font-size:14px;">
                <?php if ($user !== null): ?>
                    <?= View::e($user['name']) ?> — <?= View::e($roles[$user['role']] ?? $user['role']) ?>
                <?php endif; ?>
            </div>
        </div>
        <div class="admin-content">
            <?php foreach (['success','error','info'] as $fkey): ?>
                <?php if (!empty($flash[$fkey])): ?>
                    <div class="flash <?= $fkey ?>"><?= View::e((string) $flash[$fkey]) ?></div>
                <?php endif; ?>
            <?php endforeach; ?>
            <?= $content ?>
        </div>
    </div>
</div>
<script src="/assets/js/app.js"></script>
</body>
</html>
