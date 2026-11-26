<?php

declare(strict_types=1);

/**
 * جزئیات پیام تماس (مدیریت) — پوستهٔ PAdminLTE.
 *
 * @package Coop\Templates\Admin
 * @version 0.2.0
 * @security-level admin
 */

/** @var array<string, mixed> $__data */

$message = $__data['message'] ?? [];
$toFa = $__data['toFa'];
$subject = (string) ($message['subject'] ?? '');
$email = (string) ($message['email'] ?? '');
?>
<div class="pa-page-header pa-anim-slide-up">
    <div>
        <h1><?= View::e($subject !== '' ? $subject : 'بدون موضوع') ?></h1>
        <div class="pa-breadcrumb"><a href="/admin">پنل مدیریت</a><span class="pa-sep">›</span><a href="/admin/messages">پیام‌ها</a><span class="pa-sep">›</span><span>مشاهده</span></div>
    </div>
    <a class="pa-btn pa-btn-outline pa-btn-sm" href="/admin/messages" data-pa-icon="arrow_right"> بازگشت</a>
</div>

<div class="pa-row">
    <div class="pa-col-8">
        <div class="pa-card pa-anim-slide-up pa-anim-delay-1">
            <div class="pa-card-header">
                <h3><?= View::e($subject !== '' ? $subject : 'بدون موضوع') ?></h3>
                <?php if ($email !== ''): ?>
                    <div class="pa-tools"><a class="pa-btn pa-btn-primary pa-btn-sm" href="mailto:<?= View::e(rawurlencode($email)) ?>" data-pa-icon="reply"> پاسخ با ایمیل</a></div>
                <?php endif; ?>
            </div>
            <div class="pa-card-body">
                <p><strong>از:</strong> <?= View::e((string) ($message['name'] ?? '')) ?> &lt;<?= View::e($email) ?>&gt;</p>
                <p class="pa-text-muted pa-mb-2">تاریخ: <?= $toFa((string) mb_substr((string) ($message['created_at'] ?? ''), 0, 16, 'UTF-8')) ?></p>
                <hr style="border:0;border-top:1px solid var(--pa-border);margin:12px 0">
                <div style="white-space:pre-wrap;line-height:1.8"><?= View::e((string) ($message['message'] ?? '')) ?></div>
            </div>
        </div>
    </div>
</div>
