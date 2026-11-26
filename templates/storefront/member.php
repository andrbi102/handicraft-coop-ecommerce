<?php

declare(strict_types=1);

/**
 * صفحهٔ عضو تعاونی — پوستهٔ PAdminLTE.
 *
 * @package Coop\Templates\Storefront
 * @version 0.2.0
 * @security-level public
 */

use App\Support\Cards;

/** @var array<string, mixed> $__data */

$member = $__data['member'] ?? [];
$products = $__data['products'] ?? [];
$money = $__data['money'];
$toFa = $__data['toFa'];
$mid = (int) ($member['id'] ?? 0);
$joinYear = mb_substr((string) ($member['joined_at'] ?? ''), 0, 4, 'UTF-8');
?>
<div class="pa-breadcrumb pa-mb-1"><a href="/">خانه</a><span class="pa-sep">›</span><a href="/members">نساجان</a><span class="pa-sep">›</span><span><?= View::e((string) ($member['full_name'] ?? '')) ?></span></div>

<div class="pa-card pa-mb-2">
    <div class="pa-card-body pa-d-flex pa-gap-2 pa-align-center" style="flex-wrap:wrap">
        <div class="pa-product-thumb" style="width:96px;height:96px;border-radius:50%;flex-shrink:0">
            <span data-pa-icon="users_alt" data-pa-icon-size="48"></span>
        </div>
        <div style="flex:1;min-width:200px">
            <h1 style="margin:0 0 4px"><?= View::e((string) ($member['full_name'] ?? '')) ?></h1>
            <p class="pa-text-muted" style="margin:0"><?= View::e((string) ($member['craft'] ?? '')) ?> — <?= View::e((string) ($member['region'] ?? '')) ?></p>
            <p class="pa-text-muted pa-fs-sm" style="margin:4px 0 0">عضو تعاونی از <?= $toFa((string) $joinYear) ?></p>
        </div>
    </div>
    <?php if (!empty($member['bio'])): ?>
        <div class="pa-card-body" style="border-top:1px solid var(--pa-border);line-height:1.9"><?= nl2br(View::e((string) $member['bio'])) ?></div>
    <?php endif; ?>
</div>

<h2 class="pa-mb-1">محصولات <?= View::e((string) ($member['full_name'] ?? '')) ?></h2>
<?php if ($products === []): ?>
    <div class="pa-card pa-text-center" style="padding:32px">
        <p class="pa-text-muted">این عضو هنوز محصولی منتشر نکرده است.</p>
    </div>
<?php else: ?>
    <div class="pa-row pa-gap-2">
        <?php foreach ($products as $p): ?>
            <?= Cards::productCard($p, $money) ?>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
