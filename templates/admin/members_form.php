<?php

declare(strict_types=1);

/**
 * فرم عضو تعاونی (ایجاد/ویرایش) — پوستهٔ PAdminLTE.
 *
 * @package Coop\Templates\Admin
 * @version 0.2.0
 * @security-level admin
 */

use App\Support\Form;

/** @var array<string, mixed> $__data */

$member = $__data['member'] ?? null;
$money = $__data['money'];
$isEdit = $member !== null;
$mid = $isEdit ? (int) $member['id'] : 0;
$action = $isEdit ? '/admin/members/' . $mid : '/admin/members';
$title = $isEdit ? 'ویرایش عضو' : 'عضو جدید';
$today = date('Y-m-d');
?>
<div class="pa-page-header pa-anim-slide-up">
    <div>
        <h1><?= View::e($title) ?></h1>
        <div class="pa-breadcrumb"><a href="/admin">پنل مدیریت</a><span class="pa-sep">›</span><a href="/admin/members">نساجان</a><span class="pa-sep">›</span><span><?= View::e($title) ?></span></div>
    </div>
    <a class="pa-btn pa-btn-outline pa-btn-sm" href="/admin/members" data-pa-icon="arrow_right"> بازگشت</a>
</div>

<div class="pa-row">
    <div class="pa-col-8">
        <div class="pa-card pa-anim-slide-up pa-anim-delay-1">
            <div class="pa-card-header"><h3>مشخصات عضو</h3></div>
            <div class="pa-card-body">
                <form method="post" action="<?= View::e($action) ?>" data-pa-validate-form>
                    <?= Csrf::field() ?>
                    <div class="pa-form-group">
                        <label for="m-name">نام و نام خانوادگی *</label>
                        <input id="m-name" class="pa-input" type="text" name="full_name" data-pa-validate="required" data-pa-minlen="3" value="<?= Form::value($member, 'full_name') ?>" required>
                        <div class="pa-form-feedback"></div>
                    </div>

                    <div class="pa-form-row">
                        <div class="pa-form-group">
                            <label for="m-craft">تخصص نساجی</label>
                            <input id="m-craft" class="pa-input" type="text" name="craft" placeholder="مثلاً فرش‌بافی، گلیم، سوزن‌دوزی" value="<?= Form::value($member, 'craft') ?>">
                        </div>
                        <div class="pa-form-group">
                            <label for="m-region">منطقه / روستا</label>
                            <input id="m-region" class="pa-input" type="text" name="region" value="<?= Form::value($member, 'region') ?>">
                        </div>
                    </div>

                    <div class="pa-form-row">
                        <div class="pa-form-group">
                            <label for="m-joined">تاریخ عضویت</label>
                            <input id="m-joined" class="pa-input" type="date" name="joined_at" value="<?= Form::value($member, 'joined_at', $today) ?>">
                        </div>
                        <div class="pa-form-group">
                            <label for="m-share">سرمایه سهام (تومان)</label>
                            <input id="m-share" class="pa-input" type="text" inputmode="numeric" name="share_capital" value="<?= $isEdit ? $money((int) $member['share_capital']) : '' ?>">
                        </div>
                    </div>

                    <div class="pa-form-row">
                        <div class="pa-form-group">
                            <label for="m-phone">تلفن</label>
                            <input id="m-phone" class="pa-input" type="tel" name="phone" value="<?= Form::value($member, 'phone') ?>">
                        </div>
                        <div class="pa-form-group">
                            <label for="m-status">وضعیت</label>
                            <select id="m-status" class="pa-select" name="status">
                                <?php foreach (['active' => 'فعال', 'inactive' => 'غیرفعال'] as $k => $lbl): ?>
                                    <option value="<?= View::e($k) ?>" <?= $isEdit && $member['status'] === $k ? 'selected' : '' ?>><?= View::e($lbl) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="pa-form-group">
                        <label for="m-bio">بیوگرافی</label>
                        <textarea id="m-bio" class="pa-textarea" name="bio" rows="4"><?= Form::value($member, 'bio') ?></textarea>
                    </div>

                    <div class="pa-d-flex pa-gap-2">
                        <button type="submit" class="pa-btn pa-btn-primary" data-pa-icon="save"> ذخیره</button>
                        <a class="pa-btn pa-btn-outline" href="/admin/members">انصراف</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
