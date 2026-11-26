/**
 * اسکریپت اصلی فروشگاه تعاونی — حداقل، بدون وابستگی.
 * - تغییر تعداد سبد
 * - تأیید حذف
 * - نوار کناری مدیریت روی موبایل
 */
(function () {
    'use strict';

    // نوار کناری مدیریت روی موبایل
    var toggle = document.querySelector('.menu-toggle');
    var sidebar = document.querySelector('.admin-sidebar');
    if (toggle && sidebar) {
        toggle.addEventListener('click', function () {
            sidebar.classList.toggle('open');
        });
        document.addEventListener('click', function (e) {
            if (!sidebar.contains(e.target) && !toggle.contains(e.target)) {
                sidebar.classList.remove('open');
            }
        });
    }

    // تأیید عملیات حذف
    document.querySelectorAll('form[data-confirm]').forEach(function (form) {
        form.addEventListener('submit', function (e) {
            var msg = form.getAttribute('data-confirm') || 'آیا مطمئن هستید؟';
            if (!window.confirm(msg)) {
                e.preventDefault();
            }
        });
    });

    // دکمه‌های +/- تعداد در سبد
    document.querySelectorAll('[data-qty-step]').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var input = document.getElementById(btn.getAttribute('data-target'));
            if (!input) return;
            var step = parseInt(btn.getAttribute('data-qty-step'), 10);
            var val = parseInt(input.value, 10) || 1;
            val = Math.max(1, val + step);
            input.value = val;
        });
    });
})();
