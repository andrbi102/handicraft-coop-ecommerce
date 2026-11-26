<?php
declare(strict_types=1);
/**
 * فرم عضو تعاونی (ایجاد/ویرایش).
 * @package Coop\Templates\Admin
 * @version 0.1.0
 * @security-level admin
 */
$member = $__data['member'] ?? null;
$isEdit = $member !== null;
$action = $isEdit ? '/admin/members/' . (int) $member['id'] : '/admin/members';
$money = $__data['money'];
?>
<div class="toolbar"><h2></h2><a href="/admin/members" class="btn btn-outline btn-sm">← بازگشت</a></div>
<div class="admin-card">
    <form action="<?= $action ?>" method="post">
        <?= Csrf::field() ?>
        <div class="form-grid">
            <div class="field" style="grid-column:1/-1"><label>نام و نام خانوادگی *</label>
                <input type="text" name="full_name" required value="<?= \App\Support\Form::value($member, 'full_name') ?>"></div>
            <div class="field"><label>تخصص نساجی</label>
                <input type="text" name="craft" value="<?= \App\Support\Form::value($member, 'craft') ?>" placeholder="مثلاً فرش‌بافی، گلیم، سوزن‌دوزی"></div>
            <div class="field"><label>منطقه / روستا</label>
                <input type="text" name="region" value="<?= \App\Support\Form::value($member, 'region') ?>"></div>
            <div class="field"><label>تاریخ عضویت</label>
                <input type="date" name="joined_at" value="<?= \App\Support\Form::value($member, 'joined_at', date('Y-m-d')) ?>"></div>
            <div class="field"><label>سرمایه سهام (تومان)</label>
                <input type="text" name="share_capital" inputmode="numeric" value="<?= $isEdit ? $money((int) $member['share_capital']) : '' ?>"></div>
            <div class="field"><label>تلفن</label>
                <input type="tel" name="phone" value="<?= \App\Support\Form::value($member, 'phone') ?>"></div>
            <div class="field"><label>وضعیت</label>
                <select name="status">
                    <?php foreach (['active'=>'فعال','inactive'=>'غیرفعال'] as $k => $lbl): ?>
                        <option value="<?= $k ?>" <?= $isEdit && $member['status'] === $k ? 'selected' : '' ?>><?= $lbl ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="field" style="grid-column:1/-1"><label>بیوگرافی</label>
                <textarea name="bio"><?= \App\Support\Form::value($member, 'bio') ?></textarea></div>
        </div>
        <button class="btn btn-primary"><?= $isEdit ? 'به‌روزرسانی عضو' : 'افزودن عضو' ?></button>
    </form>
</div>
