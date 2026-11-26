<?php

declare(strict_types=1);

/**
 * صفحهٔ ۴۰۴ — محتوای داخلی (درون لایوت فروشگاه).
 *
 * @package Coop\Templates\Errors
 * @version 0.2.0
 * @security-level public
 */
?>
<div class="pa-card pa-text-center" style="max-width:480px;margin:40px auto">
    <div class="pa-card-body" style="padding:48px">
        <span data-pa-icon="search" data-pa-icon-size="64" style="opacity:.4"></span>
        <h1 class="pa-mt-1">۴۰۴ — یافت نشد</h1>
        <p class="pa-text-muted pa-mt-1"><?= View::e($__data['title'] ?? 'صفحه یا محصول موردنظر وجود ندارد یا حذف شده است.') ?></p>
        <a class="pa-btn pa-btn-primary pa-mt-2" href="/" data-pa-icon="home"> بازگشت به خانه</a>
    </div>
</div>
