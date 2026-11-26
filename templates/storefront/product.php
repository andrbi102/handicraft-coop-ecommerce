<?php
declare(strict_types=1);
/**
 * جزئیات محصول.
 * @package Coop\Templates\Storefront
 * @version 0.1.0
 * @security-level public
 */
use App\Support\Cards;
$product = $__data['product'] ?? [];
$member = $__data['member'] ?? null;
$related = $__data['related'] ?? [];
$money = $__data['money'];
$stock = (int) $product['stock'];
?>
<div class="product-detail">
    <div class="gallery">
        <?php if (!empty($product['image'])): ?>
            <img src="<?= View::e('/assets/img/' . $product['image']) ?>" alt="<?= View::e($product['name']) ?>">
        <?php else: ?>🧵<?php endif; ?>
    </div>
    <div class="info">
        <h1><?= View::e($product['name']) ?></h1>
        <div class="price-big"><?= $money((int) $product['price']) ?></div>
        <?php if ($stock <= 0): ?>
            <span class="tag out">ناموجود</span>
        <?php elseif ($stock <= 3): ?>
            <span class="tag low">تنها <?= $toFa((string) $stock) ?> عدد باقی مانده</span>
        <?php else: ?>
            <span class="tag">موجود</span>
        <?php endif; ?>

        <dl class="meta-list">
            <?php if (!empty($product['material'])): ?><dt>متریال:</dt><dd><?= View::e($product['material']) ?></dd><?php endif; ?>
            <?php if (!empty($product['dimensions'])): ?><dt>ابعاد:</dt><dd><?= View::e($product['dimensions']) ?></dd><?php endif; ?>
            <?php if (!empty($product['weight_grams'])): ?><dt>وزن:</dt><dd><?= $toFa((string) $product['weight_grams']) ?> گرم</dd><?php endif; ?>
            <?php if (!empty($product['sku'])): ?><dt>کد:</dt><dd><?= View::e($product['sku']) ?></dd><?php endif; ?>
            <?php if ($member !== null): ?>
                <dt>سازنده:</dt><dd><a href="/member/<?= (int) $member['id'] ?>"><?= View::e($member['full_name']) ?></a></dd>
            <?php endif; ?>
        </dl>

        <?php if (!empty($product['description'])): ?>
            <p><?= nl2br(View::e($product['description'])) ?></p>
        <?php endif; ?>

        <?php if ($stock > 0): ?>
        <form action="/cart/add" method="post" class="qty-row">
            <input type="hidden" name="product_id" value="<?= (int) $product['id'] ?>">
            <input type="number" name="quantity" id="qty" value="1" min="1" max="<?= $stock ?>" class="qty">
            <button type="submit" class="btn btn-primary">افزودن به سبد خرید</button>
        </form>
        <?php else: ?>
        <p class="text-muted mt-2">این محصول در حال حاضر ناموجود است.</p>
        <?php endif; ?>
    </div>
</div>

<?php if ($related !== []): ?>
<div class="section-title"><h2>محصولات مرتبط</h2></div>
<div class="product-grid">
    <?php foreach ($related as $p): ?>
        <?= Cards::productCard($p, $money) ?>
    <?php endforeach; ?>
</div>
<?php endif; ?>
