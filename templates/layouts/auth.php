<?php

declare(strict_types=1);

/**
 * چیدمان احراز هویت — کارت مرکزی pa-auth-page (بدون نوار/فوتر).
 *
 * @package Coop\Views\Layouts
 * @version 0.2.0
 * @security-level public
 */

/** @var array<string, mixed> $__data */
/** @var string $content */

$siteName = (string) ($__data['siteName'] ?? 'تعاونی نساجی');
$title = (string) ($__data['title'] ?? $siteName);
$flash = $__data['flash'] ?? [];
$brandMark = mb_substr($siteName, 0, 1, 'UTF-8') ?: 'ت';
?>
<!doctype html>
<html lang="fa" dir="rtl" data-pa-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <title><?= View::e($title) ?> — <?= View::e($siteName) ?></title>
    <link rel="stylesheet" href="/assets/padminlte/padminlte.css">
</head>
<body class="pa-auth-page">
    <div class="pa-auth-card">
        <a class="pa-auth-logo" href="/"><?= View::e($brandMark) ?></a>

        <?php foreach (['success' => 'success', 'error' => 'danger', 'info' => 'info'] as $key => $kind): ?>
            <?php $msg = trim((string) ($flash[$key] ?? '')); ?>
            <?php if ($msg !== ''): ?>
                <div class="pa-alert pa-alert-<?= $kind ?> pa-fs-sm">
                    <span data-pa-icon="<?= $kind === 'success' ? 'check_circle' : ($kind === 'danger' ? 'x_circle' : 'info') ?>"></span>
                    <div class="pa-alert-body"><?= View::e($msg) ?></div>
                </div>
            <?php endif; ?>
        <?php endforeach; ?>

        <?= $content ?>
    </div>
    <script src="/assets/padminlte/pa-icons.js"></script>
    <script src="/assets/padminlte/padminlte.js"></script>
</body>
</html>
