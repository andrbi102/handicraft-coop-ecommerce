<?php

declare(strict_types=1);

namespace App\Core;

use App\Models\CategoryModel;
use App\Models\ContactMessageModel;
use App\Models\CouponModel;
use App\Models\MemberModel;
use App\Models\OrderModel;
use App\Models\ProductModel;
use App\Models\SettingModel;
use App\Models\SettlementModel;
use App\Models\UserModel;
use App\Services\CartService;
use App\Services\OrderService;
use App\Services\SettlementService;
use Container;
use Database;

/**
 * ثبت سرویس‌ها و مدل‌ها در کانتینر — تزریق صریح، بدون autowiring جادویی.
 *
 * @package Coop\Core
 * @version 0.1.0
 * @security-level core
 */
final class ServiceProvider
{
    public static function register(Container $c): void
    {
        // — مدل‌ها —
        $c->set(UserModel::class, static fn (Container $c): UserModel => new UserModel($c->get(Database::class)));
        $c->set(MemberModel::class, static fn (Container $c): MemberModel => new MemberModel($c->get(Database::class)));
        $c->set(CategoryModel::class, static fn (Container $c): CategoryModel => new CategoryModel($c->get(Database::class)));
        $c->set(ProductModel::class, static fn (Container $c): ProductModel => new ProductModel($c->get(Database::class)));
        $c->set(OrderModel::class, static fn (Container $c): OrderModel => new OrderModel($c->get(Database::class)));
        $c->set(CouponModel::class, static fn (Container $c): CouponModel => new CouponModel($c->get(Database::class)));
        $c->set(SettlementModel::class, static fn (Container $c): SettlementModel => new SettlementModel($c->get(Database::class)));
        $c->set(ContactMessageModel::class, static fn (Container $c): ContactMessageModel => new ContactMessageModel($c->get(Database::class)));
        $c->set(SettingModel::class, static fn (Container $c): SettingModel => new SettingModel($c->get(Database::class)));

        // — سرویس‌ها —
        $c->set(CartService::class, static fn (Container $c): CartService => new CartService($c->get(ProductModel::class)));
        $c->set(SettlementService::class, static fn (Container $c): SettlementService => new SettlementService(
            $c->get(Database::class),
            $c->get(OrderModel::class),
            $c->get(SettlementModel::class),
        ));
        $c->set(OrderService::class, static fn (Container $c): OrderService => new OrderService(
            $c->get(Database::class),
            $c->get(ProductModel::class),
            $c->get(OrderModel::class),
            $c->get(CouponModel::class),
            $c->get(SettlementService::class),
        ));
    }
}
