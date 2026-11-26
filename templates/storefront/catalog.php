<?php
declare(strict_types=1);
/**
 * کاتالوگ محصولات.
 * @package Coop\Templates\Storefront
 * @version 0.1.0
 * @security-level public
 */
use App\Support\Cards;
$products = $__data['products'] ?? [];
$total = (int) ($__data['total'] ?? 0);
$page = (int) ($__data['page'] ?? 1);
$pages = (int) ($__data['pages'] ?? 1);
$sort = $__data['sort'] ?? 'new';
$q = $__data['q'] ?? '';
$currentCategory = $__data['currentCategory'] ?? null;
$categoryId = $__data['categoryId'] ?? null;
?>
<div class="section-title">
    <h2><?= View::e($__data['title']) ?></h2>
    <span class="text-muted"><?= $toFa((string) $total) ?> کالا</span>
</div>

<form method="get" action="/products" class="filters" style="margin-bottom:16px">
    <?php if ($q !== ''): ?><input type="hidden" name="q" value="<?= View::e($q) ?>"><?php endif; ?>
    <?php if ($categoryId !== null): ?><input type="hidden" name="cat" value="<?= (int) $categoryId ?>"><?php endif; ?>
    <select name="sort" onchange="this.form.submit()">
        <?php
        $sorts = ['new'=>'جدیدترین','price_asc'=>'ارزان‌ترین','price_desc'=>'گران‌ترین','name'=>'نام (الفبا)'];
        foreach ($sorts as $k => $label): ?>
            <option value="<?= View::e($k) ?>" <?= $sort === $k ? 'selected' : '' ?>><?= View::e($label) ?></option>
        <?php endforeach; ?>
    </select>
    <input type="search" name="q" value="<?= View::e($q) ?>" placeholder="جستجو…" style="min-width:200px">
    <button class="btn btn-outline btn-sm">جستجو</button>
</form>

<?php if ($products === []): ?>
    <div class="admin-card text-center">
        <p>محصولی مطابق جستجو یافت نشد.</p>
        <p class="mt-2"><a href="/products" class="btn btn-outline btn-sm">پاک کردن فیلتر</a></p>
    </div>
<?php else: ?>
<div class="product-grid">
    <?php foreach ($products as $p): ?>
        <?= Cards::productCard($p, $money) ?>
    <?php endforeach; ?>
</div>

<?php if ($pages > 1): ?>
<div class="pagination">
    <?php
    $baseQ = http_build_query(array_filter(['q' => $q, 'sort' => $sort, 'cat' => $categoryId]));
    for ($i = 1; $i <= $pages; $i++):
        $url = '/products' . ($i > 1 ? '?page=' . $i . ($baseQ ? '&' . $baseQ : '') : ($baseQ ? '?' . $baseQ : ''));
        ?>
        <?php if ($i === $page): ?>
            <span class="current"><?= $toFa((string) $i) ?></span>
        <?php else: ?>
            <a href="<?= $url ?>"><?= $toFa((string) $i) ?></a>
        <?php endif; ?>
    <?php endfor; ?>
</div>
<?php endif; ?>
<?php endif; ?>
