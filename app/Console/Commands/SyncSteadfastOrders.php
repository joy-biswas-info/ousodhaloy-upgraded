<?php
namespace App\Console\Commands;

use App\Models\Order;
use App\Services\SteadfastService;
use Illuminate\Console\Command;

class SyncSteadfastOrders extends Command
{
    protected $signature = 'steadfast:sync';
    protected $description = 'Sync order statuses from Steadfast for all active orders';

    public function handle(SteadfastService $steadfast): int
    {
        $orders = Order::whereNotNull('steadfast_consignment_id')
            ->whereNotIn('status', ['delivered', 'cancelled', 'returned', 'refunded'])
            ->get();

        $this->info("Syncing {$orders->count()} orders from Steadfast...");
        $synced = 0;

        foreach ($orders as $order) {
            if ($steadfast->syncOrderStatus($order)) {
                $this->line("  ✓ {$order->order_number} — {$order->fresh()->steadfast_status}");
                $synced++;
            }
        }

        $this->info("Done. {$synced} orders updated.");
        return 0;
    }
}