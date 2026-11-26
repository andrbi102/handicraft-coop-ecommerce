<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\OrderModel;
use App\Models\SettlementModel;
use Database;

/**
 * تسویه تعاونی — تولید و پرداخت سهم اعضا.
 *
 * وقتی سفارش به وضعیت delivered می‌رسد، به‌ازای هر قلم دارای member_id،
 * یک تسویه pending با نوع craft_wage و مبلغ = cost × quantity ساخته می‌شود.
 * (cost مزد نساجی است که در محصول تعریف شده.) حسابدار سپس آن را paid می‌کند.
 *
 * @package Coop\Services
 * @version 0.1.0
 * @security-level data
 */
final class SettlementService
{
    public function __construct(
        private Database $db,
        private OrderModel $orders,
        private SettlementModel $settlements,
    ) {
    }

    /**
     * تولید تسویه‌های pending برای یک سفارش تحویل‌شده.
     * Idempotent: اگر قبلاً برای این سفارش ساخته شده، دوباره نمی‌سازد.
     *
     * @return int تعداد تسویه‌های ایجادشده
     */
    public function generateForOrder(int $orderId): int
    {
        $existing = $this->db->query('SELECT id FROM settlements WHERE order_id = ?', [$orderId]);
        if ($existing !== []) {
            return 0; // idempotent
        }
        $items = $this->orders->items($orderId);
        $created = 0;
        foreach ($items as $item) {
            $memberId = $item['member_id'];
            if ($memberId === null || (int) $memberId <= 0) {
                continue; // قلم بدون عضو — تسویه ندارد
            }
            $amount = (int) $item['cost'] * (int) $item['quantity'];
            if ($amount <= 0) {
                continue;
            }
            $this->settlements->create([
                'member_id' => (int) $memberId,
                'order_item_id' => (int) $item['id'],
                'order_id' => $orderId,
                'amount' => $amount,
                'type' => 'craft_wage',
                'status' => 'pending',
                'period' => date('Y-m'),
                'note' => 'مزد نساجی سفارش #CO-' . $orderId,
            ]);
            $created++;
        }
        return $created;
    }

    public function markPaid(int $settlementId, string $note): int
    {
        return $this->settlements->markPaid($settlementId, $note);
    }

    /** خلاصه مالی یک عضو — برای صفحه عضو. */
    public function memberSummary(int $memberId): array
    {
        return [
            'pending' => $this->settlements->pendingForMember($memberId),
            'paid' => $this->settlements->paidForMember($memberId),
            'share_capital' => 0, // از MemberModel خوانده می‌شود
        ];
    }
}
