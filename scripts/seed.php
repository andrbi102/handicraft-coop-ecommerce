<?php

declare(strict_types=1);

/**
 * داده‌های اولیه فروشگاه تعاونی — CLI.
 *
 * اجرا: php scripts/seed.php
 * یک کاربر مدیرکل (با رمز Argon2id)، چند عضو تعاونی، دسته‌بندی، محصول،
 * کد تخفیف و تنظیمات پیش‌فرض می‌سازد. Idempotent است: اجرای مجدد، ردیف‌های
 * موجود را دور می‌زند.
 *
 * @package Coop\Scripts
 * @version 0.1.0
 * @security-level data
 */

namespace Coop\Scripts;

use App\Core\App;
use App\Core\Crypto;
use App\Core\Slug;
use App\Models\CategoryModel;
use App\Models\CouponModel;
use App\Models\MemberModel;
use App\Models\ProductModel;
use App\Models\SettingModel;
use App\Models\UserModel;

require_once dirname(__DIR__) . '/src/Core/App.php';

$c = App::boot();
$db = $c->get(\Database::class);

// فقط در صورت خالی بودن جدول‌ها seed می‌کند
if ($db->query('SELECT id FROM users LIMIT 1') !== []) {
    echo "seed: داده‌ها از قبل وجود دارند — چیزی اضافه نشد.\n";
    exit(0);
}

echo "seed: ایجاد داده‌های اولیه...\n";

// ۱) کاربران
$users = $c->get(UserModel::class);
$adminId = $users->create([
    'name' => 'مدیر تعاونی',
    'email' => 'admin@coop.local',
    'phone' => '09120000000',
    'password_hash' => Crypto::passwordHash('admin123456'),
    'role' => 'admin',
    'status' => 'active',
]);
$managerId = $users->create([
    'name' => 'مدیر فروش',
    'email' => 'manager@coop.local',
    'phone' => '09120000001',
    'password_hash' => Crypto::passwordHash('manager123456'),
    'role' => 'manager',
    'status' => 'active',
]);
$accountantId = $users->create([
    'name' => 'حسابدار تعاونی',
    'email' => 'accountant@coop.local',
    'phone' => '09120000002',
    'password_hash' => Crypto::passwordHash('accountant123456'),
    'role' => 'accountant',
    'status' => 'active',
]);
$customerId = $users->create([
    'name' => 'مشتری نمونه',
    'email' => 'customer@coop.local',
    'phone' => '09120000003',
    'password_hash' => Crypto::passwordHash('customer123456'),
    'role' => 'customer',
    'status' => 'active',
]);
echo "  کاربران: مدیرکل، مدیر فروش، حسابدار، مشتری نمونه\n";

// ۲) دسته‌بندی‌ها
$categories = $c->get(CategoryModel::class);
$catDefs = [
    ['فرش دست‌بافت', 'فرش‌ها'],
    ['گلیم و قالیچه', 'گلیم‌ها'],
    ['پارچه دست‌بافت', 'پارچه‌های نساجی'],
    ['سوزن‌دوزی و خرازی', 'سوزن‌دوزی'],
    ['شال و شالی نساجی', 'شال‌ها'],
];
$catIds = [];
foreach ($catDefs as $i => [$name, $desc]) {
    $catIds[$name] = $categories->create([
        'name' => $name,
        'slug' => Slug::make($name),
        'description' => $desc,
        'parent_id' => 0,
        'sort_order' => $i,
    ]);
}
echo "  دسته‌بندی‌ها: " . count($catIds) . " مورد\n";

// ۳) اعضای تعاونی (نساجان)
$members = $c->get(MemberModel::class);
$memberDefs = [
    ['زهرا محمدی', 'فرش‌بافی', 'کاشان', 5000000, 'نساج باتجربه فرش کاشان، بیش از ۲۰ سال در تولید قالی نیم‌قرصی.'],
    ['علی رضایی', 'گلیم‌بافی', 'شهرکرد', 3000000, 'تولید گلیم و قالیچه عشایری با طرح‌های اصیل بختیاری.'],
    ['فاطمه کریمی', 'سوزن‌دوزی', 'رفسنجان', 2000000, 'سوزن‌دوزی سنتی و خرازی رفسنجان، فعالیت از دهه ۹۰.'],
    ['حسین نجفی', 'پارچه‌بافی', 'یزد', 4000000, 'تولید پارچه‌های دست‌بافت و ترمه یزدی.'],
    ['مریم احمدی', 'شال‌بافی', 'کرمان', 2500000, 'شال و شالی پشمی نساجی با طرح‌های کرمانی.'],
];
$memberIds = [];
foreach ($memberDefs as $i => [$name, $craft, $region, $share, $bio]) {
    $memberIds[$name] = $members->create([
        'full_name' => $name,
        'craft' => $craft,
        'region' => $region,
        'joined_at' => date('Y-m-d', strtotime('-' . (2 + $i) . ' years')),
        'share_capital' => $share,
        'phone' => '0912000' . sprintf('%03d', 10 + $i),
        'bio' => $bio,
        'status' => 'active',
    ]);
}
echo "  اعضای تعاونی: " . count($memberIds) . " نفر\n";

// ۴) محصولات
$products = $c->get(ProductModel::class);
$prodDefs = [
    ['فرش کاشان ۶ متری', 'فرش دست‌بافت', 'زهرا محمدی', 18500000, 12000000, 4, 'پشم، ابریشم', '۲۰۰×۳۰۰ سانتی‌متر', 12500, true],
    ['فرش کاشان نیم‌قرصی', 'فرش دست‌بافت', 'زهرا محمدی', 9200000, 6000000, 2, 'پشم', '۱۵۰×۲۰۰ سانتی‌متر', 8400, true],
    ['گلیم بختیاری عشایری', 'گلیم و قالیچه', 'علی رضایی', 3400000, 2200000, 6, 'پشم گوسفند', '۱۲۰×۱۸۰ سانتی‌متر', 3200, true],
    ['قالیچه طرح گل میخی', 'گلیم و قالیچه', 'علی رضایی', 2800000, 1800000, 3, 'پشم', '۱۰۰×۱۵۰ سانتی‌متر', 2400, false],
    ['سوزن‌دوزی رفسنجان رومیزی', 'سوزن‌دوزی و خرازی', 'فاطمه کریمی', 950000, 650000, 8, 'نخ ابریشم، مخمل', '۶۰×۹۰ سانتی‌متر', 480, true],
    ['پرده خرزی دست‌دوز', 'سوزن‌دوزی و خرازی', 'فاطمه کریمی', 1300000, 880000, 5, 'مخمل، ابریشم', '۱۴۰×۲۰۰ سانتی‌متر', 920, false],
    ['ترمه یزدی دست‌بافت', 'پارچه دست‌بافت', 'حسین نجفی', 2100000, 1400000, 7, 'ترمه، پشم', '۱۵۰ سانتی‌متر طولی', 780, true],
    ['پارچه دست‌بافت ابریشمی', 'پارچه دست‌بافت', 'حسین نجفی', 3200000, 2100000, 2, 'ابریشم', '۱۲۰ سانتی‌متر طولی', 560, false],
    ['شال پشمی کرمانی', 'شال و شالی نساجی', 'مریم احمدی', 1450000, 950000, 12, 'پشم بز', '۷۰×۱۸۰ سانتی‌متر', 420, true],
    ['شالی نساجی طرح گل', 'شال و شالی نساجی', 'مریم احمدی', 1100000, 720000, 9, 'پشم', '۵۰×۱۶۰ سانتی‌متر', 340, false],
    ['فرش کوچک تابلویی', 'فرش دست‌بافت', 'زهرا محمدی', 3800000, 2500000, 0, 'پشم، ابریشم', '۸۰×۱۲۰ سانتی‌متر', 2100, false],
    ['گلیم ساده روستایی', 'گلیم و قالیچه', 'علی رضایی', 1800000, 1100000, 2, 'پشم', '۱۰۰×۱۶۰ سانتی‌متر', 1900, false],
];
foreach ($prodDefs as [$name, $cat, $memName, $price, $cost, $stock, $material, $dim, $weight, $featured]) {
    $products->create([
        'category_id' => $catIds[$cat],
        'member_id' => $memberIds[$memName],
        'name' => $name,
        'slug' => Slug::unique(fn (string $s): bool => $products->slugExists($s), Slug::make($name)),
        'description' => $name . ' — تولید دستی توسط ' . $memName . '، از اعضای تعاونی صنایع‌دستی نساجی.',
        'price' => $price,
        'cost' => $cost,
        'stock' => $stock,
        'sku' => 'SKU-' . strtoupper(bin2hex(random_bytes(2))),
        'weight_grams' => $weight,
        'material' => $material,
        'dimensions' => $dim,
        'image' => '',
        'is_featured' => $featured ? 1 : 0,
        'status' => 'active',
    ]);
}
echo "  محصولات: " . count($prodDefs) . " کالا\n";

// ۵) کد تخفیف
$c->get(CouponModel::class)->create([
    'code' => 'TAHVIL10',
    'type' => 'percent',
    'value' => 10,
    'min_subtotal' => 1000000,
    'usage_limit' => 100,
    'expires_at' => date('Y-m-d', strtotime('+3 months')),
    'status' => 'active',
]);
echo "  کد تخفیف: TAHVIL10 (۱۰٪)\n";

// ۶) تنظیمات پیش‌فرض
$c->get(SettingModel::class)->setMany([
    'site_name' => 'تعاونی نساجی دست‌ساز',
    'tagline' => 'فرش، گلیم و پارچه‌های دست‌بافت اهل صنعت',
    'about_text' => "این تعاونی با هدف سازمان‌دهی تولید فرش، گلیم و پارچه‌های دست‌بافت اعضای روستایی شکل گرفته است. هر محصول دست‌سازه یک عضو تعاونی است و بخش عمده‌ای از مبلغ فروش مستقیماً به‌صورت مزد نساجی به سازنده پرداخت می‌شود.",
    'contact_phone' => '031-12345678',
    'contact_email' => 'info@coop.local',
    'contact_address' => 'اصفهان، خیابان هنر، تعاونی صنایع‌دستی نساجی',
    'bank_account' => '۰۱۲۳۴۵۶۷۸۹',
    'sheba' => 'IR000000000000000000000012',
    'social_instagram' => '@coop_textile',
    'footer_text' => 'تولید شده با مهارت اعضای تعاونی',
    'announcement' => 'ارسال رایگان برای سفارش‌های بالای ۳٬۰۰۰٬۰۰۰ تومان',
]);
echo "  تنظیمات پیش‌فرض ذخیره شد\n";

echo "\nseed کامل شد.\n";
echo "  ورود مدیرکل: admin@coop.local / admin123456\n";
echo "  ورود حسابدار: accountant@coop.local / accountant123456\n";
echo "  ورود مدیر فروش: manager@coop.local / manager123456\n";
echo "  ورود مشتری: customer@coop.local / customer123456\n";
echo "\n⚠ این رمزها فقط برای دمو هستند — پس از نصب تغییر دهید.\n";
