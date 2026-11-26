<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Controllers\AdminController;
use App\Core\Request;
use App\Models\MemberModel;
use App\Models\SettlementModel;
use App\Services\SettlementService;

/**
 * مدیریت تسویه تعاونی — فهرست و تأیید پرداخت.
 *
 * @package App\Controllers\Admin
 * @version 0.1.0
 * @security-level finance
 */
final class SettlementController extends AdminController
{
    public function index(array $ctx): void
    {
        $status = (string) Request::query('status', '');
        $page = Request::queryInt('page', 1);
        $model = $this->c->get(SettlementModel::class);
        $rows = $model->allForAdmin($page, 20, $status);
        // پیوست نام عضو به هر ردیف
        $members = $this->c->get(MemberModel::class)->allActive();
        $memberMap = [];
        foreach ($members as $m) {
            $memberMap[(int) $m['id']] = $m['full_name'];
        }
        $this->renderAdmin('admin/settlements_index', [
            'section' => 'settlements',
            'title' => 'تسویه‌های تعاونی',
            'rows' => $rows,
            'memberMap' => $memberMap,
            'status' => $status,
            'page' => $page,
            'pages' => max(1, (int) ceil($model->count($status !== '' ? 'status = ?' : '', $status !== '' ? [$status] : []) / 20)),
            'totalPending' => $model->totalPending(),
            'totalPaid' => $model->totalPaid(),
        ], $ctx);
    }

    public function pay(array $ctx): void
    {
        $id = (int) ($ctx['params']['id'] ?? 0);
        $note = (string) Request::post('note', 'واریز انجام شد.');
        $this->c->get(SettlementService::class)->markPaid($id, $note);
        $this->flash('success', 'تسویه به‌عنوان پرداخت‌شده ثبت شد.');
        $this->redirect('/admin/settlements');
    }
}
