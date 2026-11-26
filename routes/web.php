<?php

declare(strict_types=1);

/**
 * نقشه مسیرها — فروشگاه (عمومی) و مدیریت.
 *
 * این فایل یک闭 برگشت می‌دهد که Router و Container را می‌گیرد و همه مسیرها
 * را ثبت می‌کند. کنترلرها به‌صورت lazy (در زمان dispatch) نمونه‌سازی می‌شوند.
 *
 * @package Coop\Routes
 * @version 0.1.0
 * @security-level core
 */

use App\Controllers\Admin\CouponController;
use App\Controllers\Admin\DashboardController;
use App\Controllers\Admin\MemberController as AdminMemberController;
use App\Controllers\Admin\MessageController;
use App\Controllers\Admin\OrderController as AdminOrderController;
use App\Controllers\Admin\ProductController as AdminProductController;
use App\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Controllers\Admin\CustomerController;
use App\Controllers\Admin\ReportController;
use App\Controllers\Admin\SettingsController as AdminSettingsController;
use App\Controllers\Admin\SettlementController;
use App\Controllers\Storefront\AccountController;
use App\Controllers\Storefront\AuthController;
use App\Controllers\Storefront\CartController;
use App\Controllers\Storefront\CatalogController;
use App\Controllers\Storefront\CheckoutController;
use App\Controllers\Storefront\HomeController;
use App\Controllers\Storefront\PageController;
use App\Controllers\Storefront\ProductController;
use App\Core\Middleware;
use Container;
use Router;

return static function (Router $r, Container $c): void {
    // کمک‌تابع: ساخت هندلر کنترلر
    $action = static function (string $class, string $method, array $guards = []) use ($c): callable {
        return static function (array $ctx) use ($class, $method, $guards, $c): void {
            foreach ($guards as $guard) {
                $guard($ctx);
            }
            $controller = new $class($c);
            $controller->{$method}($ctx);
        };
    };
    $staff = [Middleware::class, 'requireStaff'];
    $finance = [Middleware::class, 'requireFinance'];
    $auth = [Middleware::class, 'requireAuth'];

    // — بخش عمومی / فروشگاه —
    $r->get('/', $action(HomeController::class, 'index'));

    $r->get('/products', $action(CatalogController::class, 'index'));
    $r->get('/category/{slug}', $action(CatalogController::class, 'category'));
    $r->get('/product/{slug}', $action(ProductController::class, 'show'));

    $r->get('/cart', $action(CartController::class, 'index'));
    $r->post('/cart/add', $action(CartController::class, 'add'));
    $r->post('/cart/update', $action(CartController::class, 'update'));
    $r->post('/cart/remove', $action(CartController::class, 'remove'));

    $r->get('/checkout', $action(CheckoutController::class, 'index', [$auth]));
    $r->post('/checkout', $action(CheckoutController::class, 'place', [$auth]));
    $r->post('/checkout/coupon', $action(CheckoutController::class, 'applyCoupon', [$auth]));
    $r->get('/order/{number}/success', $action(CheckoutController::class, 'success', [$auth]));

    // — احراز هویت —
    $r->get('/login', $action(AuthController::class, 'loginForm'));
    $r->post('/login', $action(AuthController::class, 'login'));
    $r->get('/register', $action(AuthController::class, 'registerForm'));
    $r->post('/register', $action(AuthController::class, 'register'));
    $r->get('/logout', $action(AuthController::class, 'logout'));

    // — حساب کاربری مشتری —
    $r->get('/account', $action(AccountController::class, 'index', [$auth]));
    $r->get('/account/order/{number}', $action(AccountController::class, 'order', [$auth]));
    $r->get('/account/profile', $action(AccountController::class, 'profile', [$auth]));
    $r->post('/account/profile', $action(AccountController::class, 'updateProfile', [$auth]));
    $r->post('/account/password', $action(AccountController::class, 'changePassword', [$auth]));

    // — صفحات عمومی —
    $r->get('/about', $action(PageController::class, 'about'));
    $r->get('/contact', $action(PageController::class, 'contact'));
    $r->post('/contact', $action(PageController::class, 'contactPost'));
    $r->get('/members', $action(PageController::class, 'members'));
    $r->get('/member/{id}', $action(PageController::class, 'member'));

    // — بخش مدیریت —
    $r->get('/admin', $action(DashboardController::class, 'index', [$staff]));

    // محصولات
    $r->get('/admin/products', $action(AdminProductController::class, 'index', [$staff]));
    $r->get('/admin/products/create', $action(AdminProductController::class, 'create', [$staff]));
    $r->post('/admin/products', $action(AdminProductController::class, 'store', [$staff]));
    $r->get('/admin/products/{id}/edit', $action(AdminProductController::class, 'edit', [$staff]));
    $r->post('/admin/products/{id}', $action(AdminProductController::class, 'update', [$staff]));
    $r->post('/admin/products/{id}/delete', $action(AdminProductController::class, 'delete', [$staff]));
    $r->post('/admin/products/{id}/stock', $action(AdminProductController::class, 'adjustStock', [$staff]));

    // دسته‌بندی‌ها
    $r->get('/admin/categories', $action(AdminCategoryController::class, 'index', [$staff]));
    $r->post('/admin/categories', $action(AdminCategoryController::class, 'store', [$staff]));
    $r->get('/admin/categories/{id}/edit', $action(AdminCategoryController::class, 'edit', [$staff]));
    $r->post('/admin/categories/{id}', $action(AdminCategoryController::class, 'update', [$staff]));
    $r->post('/admin/categories/{id}/delete', $action(AdminCategoryController::class, 'delete', [$staff]));

    // سفارش‌ها
    $r->get('/admin/orders', $action(AdminOrderController::class, 'index', [$staff]));
    $r->get('/admin/orders/{id}', $action(AdminOrderController::class, 'show', [$staff]));
    $r->post('/admin/orders/{id}/status', $action(AdminOrderController::class, 'updateStatus', [$staff]));
    $r->post('/admin/orders/{id}/deliver', $action(AdminOrderController::class, 'deliver', [$staff]));

    // مشتریان
    $r->get('/admin/customers', $action(CustomerController::class, 'index', [$staff]));
    $r->get('/admin/customers/{id}', $action(CustomerController::class, 'show', [$staff]));

    // اعضای تعاونی (نساجان)
    $r->get('/admin/members', $action(AdminMemberController::class, 'index', [$staff]));
    $r->get('/admin/members/create', $action(AdminMemberController::class, 'create', [$staff]));
    $r->post('/admin/members', $action(AdminMemberController::class, 'store', [$staff]));
    $r->get('/admin/members/{id}', $action(AdminMemberController::class, 'show', [$staff]));
    $r->get('/admin/members/{id}/edit', $action(AdminMemberController::class, 'edit', [$staff]));
    $r->post('/admin/members/{id}', $action(AdminMemberController::class, 'update', [$staff]));

    // تسویه مالی (حسابدار/مدیرکل)
    $r->get('/admin/settlements', $action(SettlementController::class, 'index', [$finance]));
    $r->post('/admin/settlements/{id}/pay', $action(SettlementController::class, 'pay', [$finance]));

    // کدهای تخفیف
    $r->get('/admin/coupons', $action(CouponController::class, 'index', [$staff]));
    $r->post('/admin/coupons', $action(CouponController::class, 'store', [$staff]));
    $r->get('/admin/coupons/{id}/edit', $action(CouponController::class, 'edit', [$staff]));
    $r->post('/admin/coupons/{id}', $action(CouponController::class, 'update', [$staff]));

    // گزارش‌ها
    $r->get('/admin/reports', $action(ReportController::class, 'index', [$staff]));
    $r->get('/admin/reports/sales', $action(ReportController::class, 'sales', [$staff]));
    $r->get('/admin/reports/inventory', $action(ReportController::class, 'inventory', [$staff]));
    $r->get('/admin/reports/members', $action(ReportController::class, 'members', [$staff]));

    // تنظیمات و پیام‌ها
    $r->get('/admin/settings', $action(AdminSettingsController::class, 'index', [$staff]));
    $r->post('/admin/settings', $action(AdminSettingsController::class, 'save', [$staff]));
    $r->get('/admin/messages', $action(MessageController::class, 'index', [$staff]));
    $r->get('/admin/messages/{id}', $action(MessageController::class, 'show', [$staff]));
    $r->post('/admin/messages/{id}/read', $action(MessageController::class, 'markRead', [$staff]));
};
