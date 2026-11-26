<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Controllers\AdminController;
use App\Core\Request;
use App\Models\SettingModel;

/**
 * تنظیمات فروشگاه — اطلاعات تماس، متون صفحات، حساب بانکی، آدرس.
 *
 * @package App\Controllers\Admin
 * @version 0.1.0
 * @security-level admin
 */
final class SettingsController extends AdminController
{
    /** کلیدهای قابل ویرایش — سفید-لیست برای جلوگیری از نوشتن کلید دلخواه. */
    private const KEYS = [
        'site_name', 'tagline', 'about_text', 'contact_phone', 'contact_email',
        'contact_address', 'bank_account', 'sheba', 'social_instagram',
        'footer_text', 'announcement',
    ];

    public function index(array $ctx): void
    {
        $settings = $this->c->get(SettingModel::class)->allAsMap();
        $this->renderAdmin('admin/settings_form', [
            'section' => 'settings',
            'title' => 'تنظیمات',
            'settings' => $settings,
            'keys' => self::KEYS,
        ], $ctx);
    }

    public function save(array $ctx): void
    {
        $pairs = [];
        foreach (self::KEYS as $key) {
            $value = (string) Request::post($key, '');
            $pairs[$key] = $value;
        }
        $this->c->get(SettingModel::class)->setMany($pairs);
        $this->flash('success', 'تنظیمات ذخیره شد.');
        $this->redirect('/admin/settings');
    }
}
