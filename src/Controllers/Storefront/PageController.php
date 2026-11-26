<?php

declare(strict_types=1);

namespace App\Controllers\Storefront;

use App\Controllers\StorefrontController;
use App\Core\Request;
use App\Models\ContactMessageModel;
use App\Models\MemberModel;
use App\Models\ProductModel;
use Validator;

/**
 * صفحات عمومی — درباره تعاونی، تماس، نمایش اعضای تعاونی (نساجان).
 *
 * @package Coop\Controllers\Storefront
 * @version 0.1.0
 * @security-level public
 */
final class PageController extends StorefrontController
{
    public function about(array $ctx): void
    {
        $this->renderStore('storefront/about', ['title' => 'درباره تعاونی'], $ctx);
    }

    public function contact(array $ctx): void
    {
        $this->renderStore('storefront/contact', ['title' => 'تماس با ما'], $ctx);
    }

    public function contactPost(array $ctx): void
    {
        $name = (string) Request::post('name', '');
        $email = (string) Request::post('email', '');
        $subject = (string) Request::post('subject', '');
        $message = (string) Request::post('message', '');
        $v = new Validator(['name' => $name, 'email' => $email, 'message' => $message]);
        $v->required('name')->length('name', 3, 100)
          ->required('email')->email('email')
          ->required('message')->length('message', 10, 2000);
        if ($v->fails()) {
                    $this->flashOld();
$this->flash('error', 'لطفاً فرم را معتبر پر کنید.');$this->flash('error', 'لطفاً فرم را معتبر پر کنید.');
            $this->redirect('/contact');
        }
        $this->c->get(ContactMessageModel::class)->create([
            'name' => $name, 'email' => $email, 'subject' => $subject, 'message' => $message,
        ]);
        $this->flash('success', 'پیام شما دریافت شد. به‌زودی پاسخ می‌دهیم.');
        $this->redirect('/contact');
    }

    public function members(array $ctx): void
    {
        $members = $this->c->get(MemberModel::class)->allActive();
        $this->renderStore('storefront/members', [
            'members' => $members,
            'title' => 'نساجان تعاونی',
        ], $ctx);
    }

    public function member(array $ctx): void
    {
        $id = (int) ($ctx['params']['id'] ?? 0);
        $member = $this->c->get(MemberModel::class)->find($id);
        if ($member === null || $member['status'] !== 'active') {
            http_response_code(404);
            $this->renderStore('errors/404', ['title' => 'عضو یافت نشد'], $ctx);
            return;
        }
        $products = $this->c->get(ProductModel::class)->byMember($id);
        $this->renderStore('storefront/member', [
            'member' => $member,
            'products' => $products,
            'title' => $member['full_name'],
        ], $ctx);
    }
}
