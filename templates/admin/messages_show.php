<?php
declare(strict_types=1);
/**
 * مشاهده پیام تماس.
 * @package Coop\Templates\Admin
 * @version 0.1.0
 * @security-level admin
 */
$message = $__data['message'] ?? [];
?>
<div class="toolbar"><h2></h2><a href="/admin/messages" class="btn btn-outline btn-sm">← بازگشت</a></div>
<div class="admin-card" style="max-width:680px">
    <h2><?= View::e($message['subject'] !== '' ? $message['subject'] : 'بدون موضوع') ?></h2>
    <p class="text-muted">از: <strong><?= View::e($message['name']) ?></strong> &lt;<?= View::e($message['email']) ?>&gt;</p>
    <p class="text-muted">تاریخ: <?= $toFa((string) substr((string) $message['created_at'], 0, 16)) ?></p>
    <hr style="border:0;border-top:1px solid var(--c-border);margin:16px 0">
    <p style="white-space:pre-wrap"><?= View::e($message['message']) ?></p>
    <p class="mt-2"><a href="mailto:<?= rawurlencode((string) $message['email']) ?>" class="btn btn-primary btn-sm">پاسخ با ایمیل</a></p>
</div>
