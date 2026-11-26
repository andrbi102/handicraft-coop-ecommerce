<?php

declare(strict_types=1);

namespace App\Controllers\Storefront;

use App\Controllers\StorefrontController;
use App\Core\Auth;
use Crypto;
use App\Core\Request;
use App\Core\Slug;
use App\Models\UserModel;
use Validator;

/**
 * احراز هویت مشتری — ورود/ثبت‌نام/خروج.
 *
 * @package Coop\Controllers\Storefront
 * @version 0.1.0
 * @security-level security
 */
final class AuthController extends StorefrontController
{
    public function loginForm(array $ctx): void
    {
        if (($ctx['user'] ?? null) !== null) {
            $this->redirect('/account');
        }
        $this->renderAuth('storefront/login', [
            'title' => 'ورود',
            'return' => Request::query('return', ''),
        ], $ctx);
    }

    public function login(array $ctx): void
    {
        $email = (string) Request::post('email', '');
        $password = (string) Request::post('password', '');
        $v = new Validator(['email' => $email, 'password' => $password]);
        $v->required('email')->email('email')->required('password')->length('password', 1, 100);
        if ($v->fails()) {
                    $this->flashOld();
$this->flash('error', 'اطلاعات واردشده معتبر نیست.');$this->flash('error', 'اطلاعات واردشده معتبر نیست.');
            $this->redirect('/login');
        }
        /** @var Auth $auth */
        $auth = $ctx['auth'];
        if (!$auth->attempt($email, $password)) {
            // پیام یکسان برای هر دو حالت — جلوگیری از کشف کاربر موجود
                    $this->flashOld();
$this->flash('error', 'ایمیل یا رمز عبور نادرست است.');$this->flash('error', 'ایمیل یا رمز عبور نادرست است.');
            $this->redirect('/login');
        }
        $this->flash('success', 'خوش آمدید.');
        $return = Request::post('return', '');
        $this->redirect($return !== '' ? $return : '/account');
    }

    public function registerForm(array $ctx): void
    {
        if (($ctx['user'] ?? null) !== null) {
            $this->redirect('/account');
        }
        $this->renderAuth('storefront/register', ['title' => 'ثبت‌نام'], $ctx);
    }

    public function register(array $ctx): void
    {
        $name = (string) Request::post('name', '');
        $email = (string) Request::post('email', '');
        $phone = (string) Request::post('phone', '');
        $password = (string) Request::post('password', '');
        $confirm = (string) Request::post('password_confirm', '');

        $v = new Validator(['name' => $name, 'email' => $email, 'password' => $password]);
        $v->required('name')->length('name', 3, 80)
          ->required('email')->email('email')
          ->required('password')->length('password', 8, 100);
        if ($v->fails()) {
                    $this->flashOld();
$this->flash('error', 'لطفاً همه فیلدها را معتبر پر کنید (رمز حداقل ۸ نویسه).');$this->flash('error', 'لطفاً همه فیلدها را معتبر پر کنید (رمز حداقل ۸ نویسه).');
            $this->redirect('/register');
        }
        if ($password !== $confirm) {
                    $this->flashOld();
$this->flash('error', 'رمز عبور و تکرار آن یکسان نیستند.');$this->flash('error', 'رمز عبور و تکرار آن یکسان نیستند.');
            $this->redirect('/register');
        }
        $users = $this->c->get(UserModel::class);
        if ($users->findByEmail($email) !== null) {
                    $this->flashOld();
$this->flash('error', 'این ایمیل قبلاً ثبت شده است.');$this->flash('error', 'این ایمیل قبلاً ثبت شده است.');
            $this->redirect('/register');
        }
        $id = $users->create([
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
            'password_hash' => Crypto::passwordHash($password),
            'role' => 'customer',
            'status' => 'active',
        ]);
        /** @var Auth $auth */
        $auth = $ctx['auth'];
        $auth->loginById($id);
        $this->flash('success', 'حساب کاربری شما ایجاد شد.');
        $this->redirect('/account');
    }

    public function logout(array $ctx): void
    {
        /** @var Auth $auth */
        $auth = $ctx['auth'];
        $auth->logout();
        $this->redirect('/');
    }
}
