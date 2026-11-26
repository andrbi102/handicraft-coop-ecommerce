<?php

declare(strict_types=1);

/**
 * صفحه‌بندی سمت سرور — دکمه‌های pa-btn با پنجرهٔ اطراف صفحهٔ فعلی.
 *
 * متغیرها (از scope قالب فراخوان): $page, $pages, $baseUrl, $query (array),
 * $toFa (callable). $query برای پاس‌دادن فیلترها (مثل status) به‌کار می‌رود.
 *
 * @package Coop\Views\Partials
 * @version 0.2.0
 * @security-level public
 */

/** @var int $page */
/** @var int $pages */
/** @var string $baseUrl */
/** @var array<string, mixed> $query */
/** @var callable $toFa */

if (!isset($pages) || $pages <= 1) {
    return;
}

$page = max(1, min((int) $page, (int) $pages));
$query = $query ?? [];

/** ساخت لینک صفحه‌بندی با حفظ query-string */
$url = static function (int $n) use ($baseUrl, $query): string {
    return $baseUrl . '?' . http_build_query(array_merge($query, ['page' => $n]));
};

// پنجرهٔ ±۲ اطراف صفحهٔ فعلی + اول/آخر
$win = 2;
$start = max(1, $page - $win);
$end = min((int) $pages, $page + $win);
$nums = range($start, $end);
if (!in_array(1, $nums, true)) {
    array_unshift($nums, 1);
    if ($start > 2) {
        array_unshift($nums, 0); // نقطه‌چین
    }
}
if (!in_array((int) $pages, $nums, true)) {
    if ($end < (int) $pages - 1) {
        $nums[] = 0; // نقطه‌چین
    }
    $nums[] = (int) $pages;
}
?>
<nav class="pa-d-flex pa-gap-1 pa-justify-center pa-mt-2" aria-label="صفحه‌بندی">
    <?php if ($page > 1): ?>
        <a class="pa-btn pa-btn-outline pa-btn-sm" href="<?= View::e($url($page - 1)) ?>" rel="prev" data-pa-icon="arrow_right" data-pa-icon-size="14"></a>
    <?php else: ?>
        <span class="pa-btn pa-btn-outline pa-btn-sm" style="opacity:.4" data-pa-icon="arrow_right" data-pa-icon-size="14"></span>
    <?php endif; ?>

    <?php foreach ($nums as $n): ?>
        <?php if ($n === 0): ?>
            <span class="pa-btn pa-btn-sm" style="opacity:.6">…</span>
        <?php elseif ($n === $page): ?>
            <span class="pa-btn pa-btn-primary pa-btn-sm" aria-current="page"><?= $toFa((string) $n) ?></span>
        <?php else: ?>
            <a class="pa-btn pa-btn-outline pa-btn-sm" href="<?= View::e($url($n)) ?>"><?= $toFa((string) $n) ?></a>
        <?php endif; ?>
    <?php endforeach; ?>

    <?php if ($page < $pages): ?>
        <a class="pa-btn pa-btn-outline pa-btn-sm" href="<?= View::e($url($page + 1)) ?>" rel="next" data-pa-icon="arrow_left" data-pa-icon-size="14"></a>
    <?php else: ?>
        <span class="pa-btn pa-btn-outline pa-btn-sm" style="opacity:.4" data-pa-icon="arrow_left" data-pa-icon-size="14"></span>
    <?php endif; ?>
</nav>
