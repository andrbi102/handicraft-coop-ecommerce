<?php

declare(strict_types=1);

/**
 * صفحهٔ جزئیات محصول — پوستهٔ PAdminLTE.
 *
 * @package Coop\Templates\Storefront
 * @version 0.2.0
 * @security-level public
 */

use App\Support\Cards;

/** @var array<string, mixed> $__data */

$product = $__data['product'] ?? [];
$member = $__data['member'] ?? null;
$related = $__data['related'] ?? [];
$money = $__data['money'];
$toFa = $__data['toFa'];

$pid = (int) ($product['id'] ?? 0);
$slug = rawurlencode((string) ($product['slug'] ?? ''));
$stock = (int) ($product['stock'] ?? 0);
$grad = ['#6a4c93', '#1982c4'];
?>
<div class="pa-breadcrumb pa-mb-1"><a href="/">خانه</a><span class="pa-sep">›</span><a href="/products">محصولات</a><span class="pa-sep">›</span><span><?= View::e((string) ($product['name'] ?? '')) ?></span></div>

<div class="pa-row pa-mb-2">
    <div class="pa-col-5">
        <div class="pa-product-thumb" style="background:linear-gradient(135deg,<?= $grad[0] ?>,<?= $grad[1] ?>);display:grid;place-items:center;color:#fff;border-radius:16px;aspect-ratio:1/1">
            <span data-pa-icon="package_alt" data-pa-icon-size="120"></span>
        </div>
    </div>
    <div class="pa-col-7">
        <h1 style="margin:0 0 8px"><?= View::e((string) ($product['name'] ?? '')) ?></h1>
        <div class="pa-fs-lg" style="font-weight:700;color:var(--pa-primary);margin-bottom:12px"><?= $money((int) ($product['price'] ?? 0)) ?></div>

        <?php if ($stock <= 0): ?>
            <span class="pa-badge pa-badge-danger pa-mb-1">ناموجود</span>
        <?php elseif ($stock <= 3): ?>
            <span class="pa-badge pa-badge-warning pa-mb-1">تنها <?= $toFa((string) $stock) ?> عدد باقی مانده</span>
        <?php else: ?>
            <span class="pa-badge pa-badge-success pa-mb-1">موجود</span>
        <?php endif; ?>

        <dl class="pa-mt-1" style="display:grid;grid-template-columns:auto 1fr;gap:6px 16px;font-size:14px">
            <?php if (!empty($product['material'])): ?><dt class="pa-text-muted">متریال:</dt><dd><?= View::e((string) $product['material']) ?></dd><?php endif; ?>
            <?php if (!empty($product['dimensions'])): ?><dt class="pa-text-muted">ابعاد:</dt><dd><?= View::e((string) $product['dimensions']) ?></dd><?php endif; ?>
            <?php if ((int) ($product['weight_grams'] ?? 0) > 0): ?><dt class="pa-text-muted">وزن:</dt><dd><?= $toFa((string) $product['weight_grams']) ?> گرم</dd><?php endif; ?>
            <?php if (!empty($product['sku'])): ?><dt class="pa-text-muted">کد:</dt><dd><code><?= View::e((string) $product['sku']) ?></code></dd><?php endif; ?>
            <?php if ($member !== null): ?><dt class="pa-text-muted">سازنده:</dt><dd><a href="/member/<?= (int) $member['id'] ?>"><?= View::e((string) $member['full_name']) ?></a></dd><?php endif; ?>
        </dl>

        <?php if ($stock > 0): ?>
            <form method="post" action="/cart/add?id=<?= $pid ?>" class="pa-d-flex pa-gap-1 pa-align-center pa-mt-2" style="flex-wrap:wrap">
                <input type="hidden" name="product_id" value="<?= $pid ?>">
                <label class="pa-text-muted pa-fs-sm" for="qty">تعداد:</label>
                <input id="qty" class="pa-input" type="number" name="quantity" value="1" min="1" max="<?= $stock ?>" style="width:80px">
                <button type="submit" class="pa-btn pa-btn-primary pa-btn-lg" data-pa-icon="shopping_cart"> افزودن به سبد خرید</button>
            </form>
        <?php endif; ?>
    </div>
</div>

<?php if (!empty($product['description'])): ?>
<div class="pa-card pa-mb-2">
    <div class="pa-card-header"><h3>توضیحات</h3></div>
    <div class="pa-card-body" style="line-height:1.9"><?= nl2br(View::e((string) $product['description'])) ?></div>
</div>
<?php endif; ?>

<?php if ($related !== []): ?>
<section>
    <h2 class="pa-mb-1">محصولات مرتبط</h2>
    <div class="pa-row pa-gap-2">
        <?php foreach ($related as $p): ?>
            <?= Cards::productCard($p, $money) ?>
        <?php endforeach; ?>
    </div>
</section>
<?php endif; ?>
