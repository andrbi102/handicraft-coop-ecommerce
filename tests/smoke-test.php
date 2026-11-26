<?php

declare(strict_types=1);

/**
 * تست دود کاربردی — راه‌اندازی کامل برنامه روی یک دیتابیس موقت.
 *
 * اجرا: php tests/smoke-test.php   (خروجی ۰ = PASS)
 *
 * مرحله‌ها: boot → migrate → Argon2id → auth → کاتالوگ → سبد → سفارش →
 * کاهش موجودی → تحویل → تسویه → پرداخت تسویه.
 *
 * @package Coop\Tests
 * @version 0.1.0
 * @security-level test
 */

// دیتابیس موقت — قبل از boot تا Config آن را بخواند
$tmpDb = sys_get_temp_dir() . '/coop_smoke_' . bin2hex(random_bytes(4)) . '.sqlite';
@unlink($tmpDb);
putenv('COOP_DB_DSN=sqlite:' . $tmpDb);
putenv('COOP_DEBUG=1');
// برای نشست آزمایشی، APP_KEY ثابت
putenv('APP_KEY=smoke-test-key-not-for-production');

require_once dirname(__DIR__) . '/src/Core/App.php';

$failed = 0;
function check(string $label, bool $cond): void
{
    global $failed;
    echo $cond ? "  PASS  {$label}\n" : "  FAIL  {$label}\n";
    if (!$cond) {
        $failed++;
    }
}

$c = App\Core\App::boot();
$db = $c->get(Database::class);
check('app boots + container wired', $db instanceof Database);
check('sqlite driver', $db->isSqlite());

// مهاجرت
$pdo = $db->pdo();
$migrator = new Migrator($pdo, dirname(__DIR__) . '/migrations');
$applied = $migrator->migrate();
check('migrations applied', $applied !== []);

// Argon2id
$hash = Crypto::passwordHash('secret-pass-123');
check('argon2id hash prefix', str_starts_with($hash, '$argon2id'));
check('password verify ok', Crypto::passwordVerify('secret-pass-123', $hash));
check('password verify rejects wrong', !Crypto::passwordVerify('wrong', $hash));

// کاربر + احراز هویت
$users = $c->get(\App\Models\UserModel::class);
$uid = $users->create([
    'name' => 'تستر', 'email' => 'test@coop.local', 'phone' => '0912',
    'password_hash' => $hash, 'role' => 'customer', 'status' => 'active',
]);
check('user created with id', $uid > 0);

$auth = new \App\Core\Auth($db);
SecureSession::start();
check('auth attempt succeeds', $auth->attempt('test@coop.local', 'secret-pass-123'));
check('auth user loaded', $auth->id() === $uid);
check('auth role customer', $auth->role() === 'customer');
check('auth rejects wrong password', !$auth->attempt('test@coop.local', 'nope'));

// دسته + محصول
$categories = $c->get(\App\Models\CategoryModel::class);
$catId = $categories->create(['name' => 'فرش', 'slug' => 'farsh', 'description' => '', 'parent_id' => 0, 'sort_order' => 0]);
$members = $c->get(\App\Models\MemberModel::class);
$memId = $members->create(['full_name' => 'نساج تست', 'craft' => 'فرش', 'region' => 'کاشان', 'share_capital' => 1000000]);
$products = $c->get(\App\Models\ProductModel::class);
$pid = $products->create([
    'category_id' => $catId, 'member_id' => $memId, 'name' => 'فرش تست', 'slug' => 'test-farsh',
    'description' => '', 'price' => 1000000, 'cost' => 700000, 'stock' => 5,
    'sku' => 'T1', 'weight_grams' => 1000, 'material' => 'پشم', 'dimensions' => '۱۰۰', 'image' => '',
    'is_featured' => 1, 'status' => 'active',
]);
check('product created', $pid > 0);
check('catalog count', $products->catalogCount($catId, null) === 1);

// سبد
$cart = $c->get(\App\Services\CartService::class);
$cart->clear();
$cart->add($pid, 2);
check('cart subtotal', $cart->subtotal() === 2000000);
check('cart count', $cart->count() === 2);

// سفارش
$orderService = $c->get(\App\Services\OrderService::class);
$items = $cart->items();
$orderId = $orderService->place($uid, $items, [
    'name' => 'تستر', 'phone' => '0912', 'address' => 'نشانی تست', 'city' => 'تهران',
    'postal_code' => '12345', 'notes' => '',
], ['ok' => false, 'discount' => 0, 'reason' => '', 'coupon' => null]);
check('order placed', $orderId > 0);

// کاهش موجودی
$after = $products->find($pid);
check('stock decremented', (int) $after['stock'] === 3);

// تسویه بعد از تحویل
$settlementService = $c->get(\App\Services\SettlementService::class);
$created = $settlementService->generateForOrder($orderId);
check('settlement generated (1 craft_wage)', $created === 1);
check('settlement idempotent', $settlementService->generateForOrder($orderId) === 0);

$settlements = $c->get(\App\Models\SettlementModel::class);
$pending = $settlements->pendingForMember($memId);
// cost=700000 × qty=2 = 1400000
check('pending settlement amount = cost×qty', $pending === 1400000);

// CSRF
check('csrf token hmac-sha256', strlen(Csrf::token()) === 64);

// View escape
check('view escapes', View::e('<b>"x"</b>') === '&lt;b&gt;&quot;x&quot;&lt;/b&gt;');

@unlink($tmpDb);
echo $failed === 0 ? "\nSMOKE TEST: PASS\n" : "\nSMOKE TEST: {$failed} FAILURE(S)\n";
exit($failed === 0 ? 0 : 1);
