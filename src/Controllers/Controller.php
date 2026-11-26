<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Config;
use App\Core\Money;
use App\Core\Request;
use App\Models\CategoryModel;
use App\Models\SettingModel;
use Container;
use Database;
use SecureSession;
use View;

/**
 * پایه کنترلر — رندر با لایوت، فلش، هدایت، داده‌های مشترک.
 *
 * @package Coop\Controllers
 * @version 0.1.0
 * @security-level core
 */
abstract class Controller
{
    public function __construct(protected Container $c)
    {
    }

    protected function db(): Database
    {
        return $this->c->get(Database::class);
    }

    protected function view(): View
    {
        return $this->c->get(View::class);
    }

    /** رندر قالب داخل یک لایوت — content از قالب داخلی، سپس لایوت. */
    protected function render(string $layout, string $template, array $data = []): void
    {
        $common = $this->commonData();
        $data = array_merge($common, $data);
        // قالب داخلی: $__data به‌عنوان snapshot تزریق می‌شود (بدون content —
        // قالب داخلی به content نیاز ندارد). View::render با extract متغیرها
        // را از کلیدها می‌سازد، پس $__data هم در دسترس است.
        $content = $this->view()->render($template, $data + ['__data' => $data]);
        $data['content'] = $content;
        // لایوت: $__data را پس از افزودن content دوباره snapshot می‌کنیم تا
        // لایوت بتواند به $__data['content'] (و سایر کلیدها) دسترسی داشته باشد.
        $data['__data'] = $data;
        echo $this->view()->render('layouts/' . $layout, $data);
    }

    /** فقط قالب بدون لایوت (مثلاً صفحات خطا یا فرگمنت). */
    protected function renderOnly(string $template, array $data = []): void
    {
        $data = array_merge($this->commonData(), $data);
        $data['__data'] = $data;
        echo $this->view()->render($template, $data);
    }

    /** داده‌های مشترک همه صفحات — کاربر، فلش، تنظیمات، دسته‌ها. */
    protected function commonData(): array
    {
        $settings = $this->c->get(SettingModel::class)->allAsMap();
        $categories = $this->c->get(CategoryModel::class)->roots();
        return [
            'siteName' => $settings['site_name'] ?? (string) Config::get('name'),
            'tagline' => $settings['tagline'] ?? (string) Config::get('tagline'),
            'settings' => $settings,
            'categories' => $categories,
            'money' => \Closure::fromCallable([Money::class, 'format']),
            'num' => \Closure::fromCallable([Money::class, 'num']),
            'toFa' => \Closure::fromCallable([Money::class, 'toFa']),
        ];
    }

    protected function flash(string $key, string $message): void
    {
        SecureSession::flash($key, $message);
    }

    protected function redirect(string $path): void
    {
        Request::redirect($path);
    }

    protected function back(): void
    {
        Request::back();
    }

    /**
     * نگه‌داشتن ورودی‌های ارسالیِ کاربر (($_POST)) برای بازنمایش بعد از
     * خطای اعتبارسنجی — قبل از redirect فراخوانی شود.
     * کلیدهای حساس (رمز/توکن) در SecureSession::flashOld فیلتر می‌شوند.
     */
    protected function flashOld(): void
    {
        SecureSession::flashOld($_POST);
    }
}
