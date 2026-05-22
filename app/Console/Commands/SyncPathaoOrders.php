<?php
namespace App\Console\Commands;

use App\Models\Order;
use App\Services\PathaoService;
use Illuminate\Console\Command;

class SyncPathaoOrders extends Command
{
    protected $signature   = 'pathao:sync';
    protected $description = 'Sync order statuses from Pathao for all shipped orders';

    public function handle(PathaoService $pathao): int
    {
        $orders = Order::whereNotNull('pathao_consignment_id')
            ->whereNotIn('status', ['delivered', 'cancelled', 'returned', 'refunded'])
            ->get();

        $this->info("Syncing {$orders->count()} orders from Pathao...");
        $synced = 0;

        foreach ($orders as $order) {
            if ($pathao->syncOrderStatus($order)) {
                $this->line("  ✓ {$order->order_number} — {$order->fresh()->pathao_status}");
                $synced++;
            }
        }

        $this->info("Done. {$synced} orders updated.");
        return 0;
    }
}
