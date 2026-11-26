<?php

declare(strict_types=1);

/**
 * کاتالوگ محصولات / دسته‌بندی — پوستهٔ PAdminLTE.
 *
 * @package Coop\Templates\Storefront
 * @version 0.2.0
 * @security-level public
 */

use App\Support\Cards;

/** @var array<string, mixed> $__data */

$products = $__data['products'] ?? [];
$total = (int) ($__data['total'] ?? 0);
$page = (int) ($__data['page'] ?? 1);
$pages = (int) ($__data['pages'] ?? 1);
$sort = (string) ($__data['sort'] ?? 'new');
$q = (string) ($__data['q'] ?? '');
$currentCategory = $__data['currentCategory'] ?? null;
$categoryId = $__data['categoryId'] ?? null;
$money = $__data['money'];
$toFa = $__data['toFa'];

$title = $__data['title'] ?? 'کاتالوگ محصولات';
$sortOptions = ['new' => 'جدیدترین', 'price_asc' => 'ارزان‌ترین', 'price_desc' => 'گران‌ترین', 'name' => 'نام (الفبا)'];
?>
<div class="pa-page-header pa-anim-slide-up">
    <div>
        <h1><?= View::e((string) $title) ?></h1>
        <div class="pa-breadcrumb"><a href="/">خانه</a><span class="pa-sep">›</span><span><?= View::e((string) $title) ?></span></div>
    </div>
    <form method="get" action="/products" class="pa-d-flex pa-gap-1 pa-align-center" style="flex-wrap:wrap">
        <?php if ($q !== ''): ?><input type="hidden" name="q" value="<?= View::e($q) ?>"><?php endif; ?>
        <?php if ($categoryId !== null): ?><input type="hidden" name="cat" value="<?= View::e((string) $categoryId) ?>"><?php endif; ?>
        <select name="sort" class="pa-select pa-btn-sm" onchange="this.form.submit()">
            <?php foreach ($sortOptions as $k => $lbl): ?>
                <option value="<?= View::e($k) ?>" <?= $sort === $k ? 'selected' : '' ?>><?= View::e($lbl) ?></option>
            <?php endforeach; ?>
        </select>
        <input type="search" name="q" class="pa-input pa-btn-sm" placeholder="جستجو…" value="<?= View::e($q) ?>">
        <button type="submit" class="pa-btn pa-btn-primary pa-btn-sm" data-pa-icon="search"> جستجو</button>
    </form>
</div>

<p class="pa-text-muted pa-mb-1"><?= $toFa((string) $total) ?> کالا</p>

<?php if ($products === []): ?>
    <div class="pa-card pa-text-center" style="padding:48px">
        <span data-pa-icon="package_alt" data-pa-icon-size="48" style="opacity:.4"></span>
        <h3 class="pa-mt-1">محصولی مطابق جستجو یافت نشد.</h3>
        <a class="pa-btn pa-btn-primary pa-mt-1" href="/products">پاک کردن فیلتر</a>
    </div>
<?php else: ?>
    <div class="pa-row pa-gap-2">
        <?php foreach ($products as $p): ?>
            <?= Cards::productCard($p, $money) ?>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php
$query = array_filter(['q' => $q, 'sort' => $sort, 'cat' => $categoryId !== null ? (string) $categoryId : null], fn ($v) => $v !== '' && $v !== null);
$baseUrl = $currentCategory !== null ? '/category/' . rawurlencode((string) $currentCategory['slug']) : '/products';
include __DIR__ . '/../partials/pagination.php';
?>
