<?php

declare(strict_types=1);

/**
 * علاقه‌مندی‌ها — پوستهٔ PAdminLTE.
 *
 * @package Coop\Templates\Storefront
 * @version 0.2.0
 * @security-level public
 */

use App\Support\Cards;

/** @var array<string, mixed> $__data */

$items = $__data['items'] ?? [];
$money = $__data['money'];
$toFa = $__data['toFa'];
?>
<div class="pa-page-header pa-anim-slide-up">
    <div>
        <h1>علاقه‌مندی‌های من</h1>
        <div class="pa-breadcrumb"><a href="/">خانه</a><span class="pa-sep">›</span><span>علاقه‌مندی‌ها</span></div>
    </div>
    <span class="pa-text-muted pa-fs-sm"><?= $toFa((string) count($items)) ?> کالا</span>
</div>

<?php if ($items === []): ?>
    <div class="pa-card pa-text-center" style="padding:48px">
        <span data-pa-icon="heart" data-pa-icon-size="56" style="opacity:.4"></span>
        <h3 class="pa-mt-1">هنوز محصولی به علاقه‌مندی‌ها اضافه نکرده‌اید.</h3>
        <p class="pa-text-muted">با علامت قلب روی محصولات، آن‌ها را برای بعد ذخیره کنید.</p>
        <a class="pa-btn pa-btn-primary pa-mt-1" href="/products" data-pa-icon="package_alt"> مشاهده محصولات</a>
    </div>
<?php else: ?>
<div class="pa-row pa-gap-2">
    <?php foreach ($items as $p): ?>
        <?php
        $grad = ['#6a4c93', '#1982c4'];
        $slug = rawurlencode((string) $p['slug']);
        $pid = (int) $p['id'];
        $stock = (int) $p['stock'];
        ?>
        <div class="pa-col-4">
            <div class="pa-product-card pa-hover-lift">
                <div class="pa-product-thumb" style="background:linear-gradient(135deg,<?= $grad[0] ?>,<?= $grad[1] ?>);display:grid;place-items:center;color:#fff;position:relative">
                    <a href="/product/<?= $slug ?>" style="display:grid;place-items:center;width:100%;height:100%;color:inherit">
                        <span data-pa-icon="package_alt" data-pa-icon-size="56"></span>
                    </a>
                    <form method="post" action="/wishlist/remove" style="position:absolute;inset-block-start:8px;inset-inline-end:8px" onsubmit="return confirm('از علاقه‌مندی‌ها حذف شود؟')">
                        <?= Csrf::field() ?>
                        <input type="hidden" name="product_id" value="<?= $pid ?>">
                        <button type="submit" class="pa-icon-btn" style="background:rgba(0,0,0,.35);color:#fff" data-pa-icon="heart_alt" data-pa-icon-size="18" aria-label="حذف از علاقه‌مندی‌ها"></button>
                    </form>
                </div>
                <div class="pa-product-body">
                    <h3 class="pa-product-name"><a href="/product/<?= $slug ?>"><?= View::e((string) $p['name']) ?></a></h3>
                    <div class="pa-product-price"><span class="pa-now"><?= $money((int) $p['price']) ?></span></div>
                    <?php if ($stock > 0): ?>
                        <form method="post" action="/cart/add?id=<?= $pid ?>">
                            <?= Csrf::field() ?>
                            <input type="hidden" name="product_id" value="<?= $pid ?>">
                            <input type="hidden" name="quantity" value="1">
                            <button type="submit" class="pa-btn pa-btn-primary pa-btn-sm pa-btn-block" data-pa-icon="shopping_cart" data-pa-icon-size="14"> افزودن به سبد</button>
                        </form>
                    <?php else: ?>
                        <button type="button" class="pa-btn pa-btn-outline pa-btn-sm pa-btn-block" disabled>ناموجود</button>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>
<?php endif; ?>
