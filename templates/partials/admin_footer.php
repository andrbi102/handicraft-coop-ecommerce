<?php

declare(strict_types=1);

/**
 * فوتر پنل مدیریت.
 *
 * @package Coop\Views\Partials
 * @version 0.2.0
 * @security-level core
 */

/** @var string $siteName */
/** @var array<string, mixed> $__data */

$year = 1404; // سال جاری شمسی (تقریبی)
?>
<footer class="pa-footer" role="contentinfo">
    <div class="pa-footer-in">
        <span>© <?= View::e((string) $year) ?> <?= View::e($__data['siteName'] ?? 'تعاونی نساجی') ?> — پنل مدیریت</span>
        <span class="pa-footer-muted">ساخته‌شده با PAdminLTE — بدون وابستگی</span>
    </div>
</footer>
