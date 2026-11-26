<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Controllers\AdminController;
use App\Core\Request;
use App\Models\ContactMessageModel;

/**
 * مدیریت پیام‌های تماس.
 *
 * @package App\Controllers\Admin
 * @version 0.1.0
 * @security-level admin
 */
final class MessageController extends AdminController
{
    public function index(array $ctx): void
    {
        $page = Request::queryInt('page', 1);
        $model = $this->c->get(ContactMessageModel::class);
        $this->renderAdmin('admin/messages_index', [
            'section' => 'messages',
            'title' => 'پیام‌های دریافتی',
            'messages' => $model->all($page, 20),
            'page' => $page,
            'pages' => max(1, (int) ceil($model->count() / 20)),
        ], $ctx);
    }

    public function show(array $ctx): void
    {
        $id = (int) ($ctx['params']['id'] ?? 0);
        $model = $this->c->get(ContactMessageModel::class);
        $message = $model->find($id);
        if ($message === null) {
            $this->flash('error', 'پیام یافت نشد.');
            $this->redirect('/admin/messages');
        }
        if ((int) $message['is_read'] === 0) {
            $model->markRead($id);
        }
        $this->renderAdmin('admin/messages_show', [
            'section' => 'messages',
            'title' => 'مشاهده پیام',
            'message' => $message,
        ], $ctx);
    }

    public function markRead(array $ctx): void
    {
        $id = (int) ($ctx['params']['id'] ?? 0);
        $this->c->get(ContactMessageModel::class)->markRead($id);
        $this->redirect('/admin/messages');
    }
}
