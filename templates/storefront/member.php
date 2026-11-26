<?php
declare(strict_types=1);
/**
 * پروفایل عضو تعاونی (نساج) + محصولات.
 * @package Coop\Templates\Storefront
 * @version 0.1.0
 * @security-level public
 */
use App\Support\Cards;
$member = $__data['member'] ?? [];
$products = $__data['products'] ?? [];
?>
<div class="admin-card">
    <div class="row" style="align-items:center;gap:16px">
        <div style="font-size:48px">🧑‍🌾</div>
        <div>
            <h1><?= View::e($member['full_name']) ?></h1>
            <p class="text-muted"><?= View::e($member['craft']) ?> — <?= View::e($member['region']) ?></p>
            <p class="text-muted" style="font-size:13px">عضو تعاونی از <?= $toFa((string) substr((string) $member['joined_at'], 0, 4)) ?></p>
        </div>
    </div>
    <?php if (!empty($member['bio'])): ?>
        <p class="mt-2"><?= nl2br(View::e($member['bio'])) ?></p>
    <?php endif; ?>
</div>

<div class="section-title"><h2>محصولات <?= View::e($member['full_name']) ?></h2></div>
<?php if ($products === []): ?>
<div class="admin-card text-center"><p>این عضو هنوز محصولی منتشر نکرده است.</p></div>
<?php else: ?>
<div class="product-grid">
    <?php foreach ($products as $p): ?>
        <?= Cards::productCard($p, $money) ?>
    <?php endforeach; ?>
</div>
<?php endif; ?>
