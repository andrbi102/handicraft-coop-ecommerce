<?php
declare(strict_types=1);
/**
 * نساجان تعاونی — نمایش عمومی.
 * @package Coop\Templates\Storefront
 * @version 0.1.0
 * @security-level public
 */
use App\Support\Cards;
$members = $__data['members'] ?? [];
?>
<div class="hero" style="margin-bottom:24px">
    <div class="container">
        <h1>نساجان تعاونی</h1>
        <p>آشنایی با اهل صنعتی که هر کالای این فروشگاه از دست‌های آن‌ها متولد می‌شود.</p>
    </div>
</div>
<?php if ($members === []): ?>
<div class="admin-card text-center"><p>هنوز عضوی معرفی نشده است.</p></div>
<?php else: ?>
<div class="product-grid">
    <?php foreach ($members as $m): ?>
        <?= Cards::memberCard($m) ?>
    <?php endforeach; ?>
</div>
<?php endif; ?>
