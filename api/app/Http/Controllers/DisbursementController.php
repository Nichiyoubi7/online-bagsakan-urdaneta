<?php
namespace App\Http\Controllers;

use App\Models\Disbursement;
use App\Models\Notification;
use Illuminate\Http\Request;

class DisbursementController extends Controller
{
    // Get all disbursements (admin)
    public function index()
    {
        $disbursements = Disbursement::with('seller', 'order')
            ->orderBy('created_at', 'desc')
            ->paginate(50);
        return response()->json($disbursements);
    }

    // Release all pending disbursements (admin manually triggers)
    public function releaseAll()
    {
        $pending = Disbursement::with('seller', 'order')
            ->where('status', 'pending')
            ->get();

        if ($pending->isEmpty()) {
            return response()->json(['message' => 'No pending disbursements.', 'released' => 0]);
        }

        $released = 0;
        foreach ($pending as $disbursement) {
            $disbursement->update([
                'status'      => 'released',
                'released_at' => now(),
            ]);

            // Update order payment status
            if ($disbursement->order) {
                $disbursement->order->update(['payment_status' => 'disbursed']);
            }

            // Notify seller
            $gcash = $disbursement->seller_gcash ?? 'your registered GCash';
            Notification::create([
                'user_id'  => $disbursement->seller_id,
                'title'    => 'Payment Released! 💚',
                'message'  => "₱" . number_format($disbursement->amount, 2) . " for Order #{$disbursement->order_id} has been released to your GCash: {$gcash}. Please check your GCash account.",
                'type'     => 'success',
                'icon'     => '💸',
                'order_id' => $disbursement->order_id,
            ]);

            $released++;
        }

        return response()->json([
            'message'  => "Successfully released {$released} disbursement(s).",
            'released' => $released,
        ]);
    }

    // Release a single disbursement (admin)
    public function releaseSingle($id)
    {
        $disbursement = Disbursement::with('seller', 'order')->findOrFail($id);

        if ($disbursement->status === 'released') {
            return response()->json(['message' => 'Already released.'], 422);
        }

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

        return response()->json(['message' => 'Payment released successfully!', 'disbursement' => $disbursement]);
    }
}