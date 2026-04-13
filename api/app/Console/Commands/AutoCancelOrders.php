<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Order;
use App\Models\Notification;
use Carbon\Carbon;

class AutoCancelOrders extends Command
{
    protected $signature   = 'orders:auto-cancel';
    protected $description = 'Auto-cancel ready orders with no driver after 2 minutes';

    public function handle()
    {
        $expiredOrders = Order::where('status', 'ready')
            ->whereNull('driver_id')
            ->where('updated_at', '<=', Carbon::now()->subMinutes(2))
            ->get();

        foreach ($expiredOrders as $order) {
            $order->update(['status' => 'cancelled']);

            // Notify customer
            Notification::create([
                'user_id'  => $order->customer_id,
                'title'    => 'Order Auto-Cancelled ❌',
                'message'  => "Order #{$order->id} was cancelled because no driver accepted within 2 minutes.",
                'type'     => 'warning',
                'icon'     => '❌',
                'order_id' => $order->id,
            ]);

            // Notify seller
            Notification::create([
                'user_id'  => $order->seller_id,
                'title'    => 'Order Auto-Cancelled ❌',
                'message'  => "Order #{$order->id} was cancelled because no driver accepted within 2 minutes.",
                'type'     => 'warning',
                'icon'     => '❌',
                'order_id' => $order->id,
            ]);

            $this->info("Order #{$order->id} auto-cancelled.");
        }

        $this->info('Auto-cancel check complete.');
    }
}