<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Controllers\AdminController;
use App\Core\Request;
use App\Models\CouponModel;
use Validator;

/**
 * مدیریت کدهای تخفیف.
 *
 * @package App\Controllers\Admin
 * @version 0.1.0
 * @security-level admin
 */
final class CouponController extends AdminController
{
    public function index(array $ctx): void
    {
        $page = Request::queryInt('page', 1);
        $model = $this->c->get(CouponModel::class);
        $this->renderAdmin('admin/coupons_index', [
            'section' => 'coupons',
            'title' => 'کدهای تخفیف',
            'coupons' => $model->all($page, 20),
            'page' => $page,
            'pages' => max(1, (int) ceil($model->count() / 20)),
        ], $ctx);
    }

    public function store(array $ctx): void
    {
        $data = $this->validate(null);
        if ($data === null) {
            $this->redirect('/admin/coupons');
        }
        $this->c->get(CouponModel::class)->create($data);
        $this->flash('success', 'کد تخفیف ایجاد شد.');
        $this->redirect('/admin/coupons');
    }

    public function edit(array $ctx): void
    {
        $id = (int) ($ctx['params']['id'] ?? 0);
        $coupon = $this->c->get(CouponModel::class)->find($id);
        if ($coupon === null) {
            $this->flash('error', 'کد تخفیف یافت نشد.');
            $this->redirect('/admin/coupons');
        }
        $this->renderAdmin('admin/coupons_form', [
            'section' => 'coupons',
            'title' => 'ویرایش کد تخفیف',
            'coupon' => $coupon,
        ], $ctx);
    }

    public function update(array $ctx): void
    {
        $id = (int) ($ctx['params']['id'] ?? 0);
        $coupon = $this->c->get(CouponModel::class)->find($id);
        if ($coupon === null) {
            $this->flash('error', 'کد تخفیف یافت نشد.');
            $this->redirect('/admin/coupons');
        }
        $data = $this->validate($coupon);
        if ($data === null) {
            $this->redirect('/admin/coupons/' . $id . '/edit');
        }
        $this->c->get(CouponModel::class)->update($id, $data);
        $this->flash('success', 'کد تخفیف به‌روزرسانی شد.');
        $this->redirect('/admin/coupons');
    }

    /** @return array<string, mixed>|null */
    private function validate(?array $existing): ?array
    {
        $code = (string) Request::post('code', '');
        $type = (string) Request::post('type', 'fixed');
        $value = (int) Request::post('value', '0');
        $v = new Validator(['code' => $code, 'value' => $value]);
        $v->required('code')->length('code', 3, 30)
          ->required('value')->int('value', 1);
        if ($v->fails()) {
            $this->flash('error', 'کد و مقدار تخفیف معتبر نیستند.');
            return null;
        }
        return [
            'code' => $code,
            'type' => in_array($type, ['percent', 'fixed'], true) ? $type : 'fixed',
            'value' => $value,
            'min_subtotal' => (int) Request::post('min_subtotal', '0'),
            'usage_limit' => (int) Request::post('usage_limit', '0'),
            'expires_at' => (string) Request::post('expires_at', ''),
            'status' => (string) Request::post('status', 'active'),
        ];
    }
}
