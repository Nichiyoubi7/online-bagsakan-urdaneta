<?php
namespace App\Console\Commands;

use App\Models\Disbursement;
use App\Models\Notification;
use Illuminate\Console\Command;

class ReleaseDisbursements extends Command
{
    protected $signature   = 'obra:release-disbursements';
    protected $description = 'Release all pending seller disbursements (runs at 8AM daily)';

    public function handle()
    {
        $pending = Disbursement::with('seller', 'order')
            ->where('status', 'pending')
            ->get();

        if ($pending->isEmpty()) {
            $this->info('No pending disbursements.');
            return;
        }

        foreach ($pending as $disbursement) {
            $disbursement->update([
                'status'      => 'released',
                'released_at' => now(),
            ]);

            if ($disbursement->order) {
                $disbursement->order->update(['payment_status' => 'disbursed']);
            }

            $gcash = $disbursement->seller_gcash ?? 'your registered GCash';
            Notification::create([
                'user_id'  => $disbursement->seller_id,
                'title'    => 'Payment Released! 💚',
                'message'  => "₱" . number_format($disbursement->amount, 2) . " for Order #{$disbursement->order_id} has been released to your GCash: {$gcash}. Please check your GCash account.",
                'type'     => 'success',
                'icon'     => '💸',
                'order_id' => $disbursement->order_id,
            ]);
        }

        $this->info("Released {$pending->count()} disbursement(s).");
    }
}