<?php
declare(strict_types=1);
/**
 * صفحه اصلی فروشگاه.
 * @package Coop\Templates\Storefront
 * @version 0.1.0
 * @security-level public
 */
use App\Support\Cards;
$featured = $__data['featured'] ?? [];
$latest = $__data['latest'] ?? [];
$settings = $__data['settings'] ?? [];
$announcement = $settings['announcement'] ?? '';
$about = $settings['about_text'] ?? '';
?>
<?php if ($announcement !== ''): ?>
    <div class="flash info" style="text-align:center"><?= View::e($announcement) ?></div>
<?php endif; ?>

<section class="hero">
    <div class="container">
        <h1><?= View::e($__data['siteName']) ?></h1>
        <p><?= View::e($__data['tagline']) ?> — فرش، گلیم، پارچه‌های دست‌بافت و صنایع‌دستی نساجی روستایی، تولید اعضای تعاونی.</p>
        <a href="/products" class="btn btn-accent">مشاهده محصولات</a>
        <a href="/members" class="btn btn-outline" style="color:#fff;border-color:#fff">آشنا با نساجان</a>
    </div>
</section>

<?php if ($featured !== []): ?>
<div class="section-title">
    <h2>محصولات منتخب</h2>
    <a href="/products">همه →</a>
</div>
<div class="product-grid">
    <?php foreach ($featured as $p): ?>
        <?= Cards::productCard($p, $money) ?>
    <?php endforeach; ?>
</div>
<?php endif; ?>

<?php if ($latest !== []): ?>
<div class="section-title">
    <h2>تازه‌های تولید</h2>
    <a href="/products?sort=new">بیشتر →</a>
</div>
<div class="product-grid">
    <?php foreach ($latest as $p): ?>
        <?= Cards::productCard($p, $money) ?>
    <?php endforeach; ?>
</div>
<?php endif; ?>

<?php if ($about !== ''): ?>
<div class="section-title"><h2>درباره تعاونی</h2></div>
<div class="admin-card">
    <p><?= nl2br(View::e($about)) ?></p>
    <p class="mt-2"><a href="/about">بیشتر بدانید →</a></p>
</div>
<?php endif; ?>
