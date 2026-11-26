<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Controllers\AdminController;
use App\Core\Money;
use App\Core\Request;
use App\Models\MemberModel;
use App\Models\ProductModel;
use App\Models\SettlementModel;
use App\Services\SettlementService;
use Validator;

/**
 * مدیریت اعضای تعاونی (نساجان/هنرمندان) — پروفایل، محصولات، وضعیت مالی.
 *
 * @package App\Controllers\Admin
 * @version 0.1.0
 * @security-level admin
 */
final class MemberController extends AdminController
{
    public function index(array $ctx): void
    {
        $page = Request::queryInt('page', 1);
        $model = $this->c->get(MemberModel::class);
        $members = $model->paginate('1=1', [], ['id' => 'DESC'], $page, 15);
        $this->renderAdmin('admin/members_index', [
            'section' => 'members',
            'title' => 'نساجان تعاونی',
            'members' => $members,
            'page' => $page,
            'pages' => max(1, (int) ceil($model->count() / 15)),
        ], $ctx);
    }

    public function create(array $ctx): void
    {
        $this->renderAdmin('admin/members_form', [
            'section' => 'members',
            'title' => 'عضو جدید',
            'member' => null,
        ], $ctx);
    }

    public function store(array $ctx): void
    {
        $data = $this->validate();
        if ($data === null) {
            $this->redirect('/admin/members/create');
        }
        $this->c->get(MemberModel::class)->create($data);
        $this->flash('success', 'عضو تعاونی اضافه شد.');
        $this->redirect('/admin/members');
    }

    public function edit(array $ctx): void
    {
        $id = (int) ($ctx['params']['id'] ?? 0);
        $member = $this->c->get(MemberModel::class)->find($id);
        if ($member === null) {
            $this->flash('error', 'عضو یافت نشد.');
            $this->redirect('/admin/members');
        }
        $this->renderAdmin('admin/members_form', [
            'section' => 'members',
            'title' => 'ویرایش عضو',
            'member' => $member,
        ], $ctx);
    }

    public function update(array $ctx): void
    {
        $id = (int) ($ctx['params']['id'] ?? 0);
        $member = $this->c->get(MemberModel::class)->find($id);
        if ($member === null) {
            $this->flash('error', 'عضو یافت نشد.');
            $this->redirect('/admin/members');
        }
        $data = $this->validate();
        if ($data === null) {
            $this->redirect('/admin/members/' . $id . '/edit');
        }
        $this->c->get(MemberModel::class)->update($id, $data);
        $this->flash('success', 'اطلاعات عضو به‌روزرسانی شد.');
        $this->redirect('/admin/members');
    }

    public function show(array $ctx): void
    {
        $id = (int) ($ctx['params']['id'] ?? 0);
        $model = $this->c->get(MemberModel::class);
        $member = $model->find($id);
        if ($member === null) {
            $this->flash('error', 'عضو یافت نشد.');
            $this->redirect('/admin/members');
        }
        $products = $this->c->get(ProductModel::class)->byMember($id);
        $settlements = $this->c->get(SettlementModel::class)->forMember($id, 1, 50);
        $summary = $this->c->get(SettlementService::class)->memberSummary($id);
        $this->renderAdmin('admin/members_show', [
            'section' => 'members',
            'title' => 'عضو: ' . $member['full_name'],
            'member' => $member,
            'products' => $products,
            'settlements' => $settlements,
            'pending' => $summary['pending'],
            'paid' => $summary['paid'],
            'productCount' => $model->productCount($id),
        ], $ctx);
    }

    /** @return array<string, mixed>|null */
    private function validate(): ?array
    {
        $fullName = (string) Request::post('full_name', '');
        $v = new Validator(['full_name' => $fullName]);
        $v->required('full_name')->length('full_name', 3, 100);
        if ($v->fails()) {
                    $this->flashOld();
$this->flash('error', 'نام عضو معتبر نیست.');$this->flash('error', 'نام عضو معتبر نیست.');
            return null;
        }
        return [
            'full_name' => $fullName,
            'craft' => (string) Request::post('craft', ''),
            'region' => (string) Request::post('region', ''),
            'joined_at' => (string) Request::post('joined_at', date('Y-m-d')),
            'share_capital' => Money::parse((string) Request::post('share_capital', '0')),
            'phone' => (string) Request::post('phone', ''),
            'bio' => (string) Request::post('bio', ''),
            'status' => (string) Request::post('status', 'active'),
        ];
    }
}
