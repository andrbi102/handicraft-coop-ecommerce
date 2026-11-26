<?php

declare(strict_types=1);

/**
 * سوالات متداول — پوستهٔ PAdminLTE.
 *
 * از <details> بومی استفاده می‌شود — بدون جاوااسکریپت، قابل‌دسترسی با کیبورد
 * و صفحه‌خوان، و حالت باز/بسته توسط مرورگر مدیریت می‌شود.
 *
 * @package Coop\Templates\Storefront
 * @version 0.2.0
 * @security-level public
 */

/** @var array<string, mixed> $__data */
$settings = $__data['settings'] ?? [];
$siteName = $__data['siteName'];

// دسته‌بندی سوالات — هر گروه فهرستی از [سوال، پاسخ]
$groups = [
    'سفارش و خرید' => [
        ['چطور سفارش بدهم؟', 'محصول را به سبد خرید اضافه کنید، به صفحهٔ تسویه حساب بروید، نشانی تحویل را وارد کرده و سفارش را نهایی کنید. پس از تأیید پنل مدیریت، وضعیت در «پیگیری سفارش» یا «حساب من» قابل مشاهده است.'],
        ['آیا باید ثبت‌نام کنم؟', 'برای تسویه حساب بله — یک حساب مشتری رایگان می‌سازیم تا سفارش‌هایتان پیگیری شوند. اما مرور کاتالوگ و علاقه‌مندی‌ها بدون ورود ممکن است.'],
        ['می‌توانم سفارش را پیگیری کنم؟', 'بله — از صفحهٔ «پیگیری سفارش» با شماره سفارش و تلفن ثبت‌شده، وضعیت را ببینید.'],
    ],
    'پرداخت و ارسال' => [
        ['روش پرداخت چیست؟', 'پرداخت کارت‌به‌کارت یا در محل است. پس از ثبت سفارش، شماره حساب و شبا تعاونی در اختیار شما قرار می‌گیرد و پس از تأیید پرداخت، سفارش آماده‌سازی می‌شود.'],
        ['هزینه و زمان ارسال؟', 'ارسال در تهران رایگان است و برای شهرستان‌ها هزینه بر اساس وزن محاسبه می‌شود. تحویل عموماً ۳ تا ۷ روز کاری پس از تأیید پرداخت انجام می‌شود.'],
        ['آیا تخفیف دارید؟', 'بله — کدهای تخفیف فصلی و تخفیف ویژه برای اعضای تعاونی ارائه می‌شود. کد را در صفحهٔ تسویه حساب وارد کنید.'],
    ],
    'محصولات و نساجان' => [
        ['محصولات دست‌بافت هستند؟', 'بله — همهٔ محصولات (فرش، گلیم، پارچه و صنایع‌دستی) توسط اعضای تعاونی به‌صورت دست‌بافت تولید می‌شوند. سازندهٔ هر محصول در صفحهٔ محصول معرفی شده است.'],
        ['تفاوت محصول با علاقه‌مندی چیست؟', 'علاقه‌مندی فقط محصول را برای بعد ذخیره می‌کند و خریدی ثبت نمی‌کند. برای خرید باید آن را به سبد خرید اضافه کنید.'],
        ['اگر محصول ناموجود شد؟', 'محصولات ناموجود با نشان «ناموجود» علامت‌گذاری می‌شوند. می‌توانید آن‌ها را به علاقه‌مندی‌ها اضافه کنید تا هنگام بازگشایی موجودی بررسی کنید.'],
    ],
    'تعاونی و عضویت' => [
        ['تعاونی چیست؟', 'تعاونی نساجی یک مجموعهٔ تولیدی روستایی است که اعضا (نساجان) سرمایه سهام می‌گذارند، تولید می‌کنند و تسویهٔ شفاف مالی دریافت می‌کنند — بدون واسطه. اطلاعات بیشتر در صفحهٔ «درباره تعاونی».'],
        ['چطور عضو تعاونی شوم؟', 'عضویت نساجان از طریق پنل مدیریت و توسط مدیر تعاونی انجام می‌شود. برای اطلاع بیشتر با ما تماس بگیرید.'],
        ['تسویهٔ نساجان چطور است؟', 'پس از تحویل نهایی هر سفارش، سهم مزد نساجِ سازنده به‌صورت رکورد تسویه ثبت می‌شود و در گزارش عملکرد اعضا قابل پیگیری است.'],
    ],
];
?>
<div class="pa-page-header pa-anim-slide-up">
    <div>
        <h1>سوالات متداول</h1>
        <div class="pa-breadcrumb"><a href="/">خانه</a><span class="pa-sep">›</span><span>سوالات متداول</span></div>
    </div>
</div>

<div class="pa-row">
    <div class="pa-col-8">
        <?php foreach ($groups as $title => $qa): ?>
            <div class="pa-card pa-mb-2">
                <div class="pa-card-header"><h3><?= View::e($title) ?></h3></div>
                <div class="pa-card-body" style="padding:0">
                    <?php foreach ($qa as [$q, $a]): ?>
                        <details class="pa-faq-item" style="border-bottom:1px solid var(--pa-border)">
                            <summary class="pa-faq-q" style="padding:14px 18px;font-weight:600;cursor:pointer;display:flex;justify-content:space-between;align-items:center;gap:8px">
                                <span><?= View::e($q) ?></span>
                                <span data-pa-icon="chevron_down" style="transition:transform .2s;flex-shrink:0"></span>
                            </summary>
                            <div class="pa-faq-a" style="padding:0 18px 16px;line-height:1.9;color:var(--pa-muted-fg,#5a6470)"><?= View::e($a) ?></div>
                        </details>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <div class="pa-col-4">
        <div class="pa-card">
            <div class="pa-card-header"><h3>پاسخ خود را نیافتید؟</h3></div>
            <div class="pa-card-body">
                <p class="pa-text-muted">تیم پشتیبانی <?= View::e((string) $siteName) ?> در دسترس است.</p>
                <p>تلفن: <?= View::e((string) ($settings['contact_phone'] ?? '—')) ?></p>
                <p>ایمیل: <?= View::e((string) ($settings['contact_email'] ?? '—')) ?></p>
                <a class="pa-btn pa-btn-primary pa-btn-block pa-mt-2" href="/contact" data-pa-icon="envelope"> ارسال پیام</a>
            </div>
        </div>
    </div>
</div>

<style>
/* چرخش آیکون chevron هنگام بازشدن details */
details.pa-faq-item[open] > summary .pa-chevron,
details.pa-faq-item[open] > summary [data-pa-icon="chevron_down"] { transform: rotate(180deg); }
details.pa-faq-item > summary::-webkit-details-marker { display: none; }
details.pa-faq-item > summary { list-style: none; }
</style>
