<?php
namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class PaymentController extends Controller
{
    public function uploadReceipt(Request $request, $orderId)
    {
        $request->validate([
            'receipt' => 'required|image|mimes:jpeg,png,jpg|max:5120',
        ]);

        $order = Order::with(['seller', 'customer'])->findOrFail($orderId);

        // Store the receipt image
        $path = $request->file('receipt')->store('gcash_receipts', 'public');

        $order->update([
            'gcash_receipt'  => $path,
            'payment_status' => 'verifying',
        ]);

        // Call Claude AI to verify the receipt
        $verificationResult = $this->verifyReceiptWithAI($path, $order->total);

        if ($verificationResult['verified']) {
            $order->update([
                'payment_verified'    => true,
                'payment_verified_at' => now(),
                'payment_status'      => 'paid',
                'gcash_ref_no'        => $verificationResult['ref_no'] ?? null,
                'ai_verification_result' => json_encode($verificationResult),
                'status'              => 'confirmed',
            ]);

            // Notify seller
            Notification::create([
                'user_id'  => $order->seller_id,
                'title'    => 'GCash Payment Verified! ✅',
                'message'  => "Order #{$order->id} payment of ₱{$order->total} has been verified by AI. Please prepare the order.",
                'type'     => 'success',
                'icon'     => '💚',
                'order_id' => $order->id,
            ]);

            // Notify customer
            Notification::create([
                'user_id'  => $order->customer_id,
                'title'    => 'Payment Confirmed! 🎉',
                'message'  => "Your GCash payment for Order #{$order->id} has been verified. The seller will now prepare your order.",
                'type'     => 'success',
                'icon'     => '✅',
                'order_id' => $order->id,
            ]);

            return response()->json([
                'verified' => true,
                'message'  => 'Payment verified successfully!',
                'result'   => $verificationResult,
                'order'    => $order->load('items'),
            ]);
        } else {
            $order->update([
                'payment_status'         => 'verification_failed',
                'ai_verification_result' => json_encode($verificationResult),
            ]);

            return response()->json([
                'verified' => false,
                'message'  => $verificationResult['reason'] ?? 'Payment verification failed. Please check the receipt and try again.',
                'result'   => $verificationResult,
            ], 422);
        }
    }

    private function verifyReceiptWithAI(string $imagePath, float $orderTotal): array
    {
        try {
            $fullPath  = Storage::disk('public')->path($imagePath);
            $imageData = base64_encode(file_get_contents($fullPath));
            $mimeType  = mime_content_type($fullPath);

            $prompt = "You are verifying a GCash payment receipt for an e-commerce order.

The expected payment amount is: ₱" . number_format($orderTotal, 2) . "
The recipient GCash number is: 09660818437

Please analyze this GCash receipt image and extract:
1. The amount paid
2. The reference number
3. The recipient number or name
4. The transaction date and time
5. Whether this appears to be a legitimate GCash receipt

Then determine if this payment is VALID for this order by checking:
- Does the amount match ₱" . number_format($orderTotal, 2) . " (allow ±₱1 difference for rounding)?
- Does the recipient number contain 09660818437?
- Is the receipt format consistent with a real GCash receipt?

Respond ONLY in this exact JSON format:
{
  \"verified\": true or false,
  \"amount_found\": \"the amount you found in the receipt\",
  \"ref_no\": \"the reference number\",
  \"recipient\": \"the recipient number or name\",
  \"transaction_date\": \"the date and time\",
  \"amount_matches\": true or false,
  \"recipient_matches\": true or false,
  \"looks_legitimate\": true or false,
  \"reason\": \"brief explanation of your decision\"
}";

            $response = Http::withHeaders([
                'x-api-key'         => env('ANTHROPIC_API_KEY'),
                'anthropic-version' => '2023-06-01',
                'content-type'      => 'application/json',
            ])->post('https://api.anthropic.com/v1/messages', [
                'model'      => 'claude-opus-4-5',
                'max_tokens' => 1024,
                'messages'   => [
                    [
                        'role'    => 'user',
                        'content' => [
                            [
                                'type'   => 'image',
                                'source' => [
                                    'type'       => 'base64',
                                    'media_type' => $mimeType,
                                    'data'       => $imageData,
                                ],
                            ],
                            [
                                'type' => 'text',
                                'text' => $prompt,
                            ],
                        ],
                    ],
                ],
            ]);

            if ($response->successful()) {
                $content = $response->json('content.0.text');
                // Clean JSON from response
                $content = preg_replace('/```json\s*|\s*```/', '', $content);
                $result  = json_decode(trim($content), true);

                if (json_last_error() === JSON_ERROR_NONE) {
                    return $result;
                }
            }

            return [
                'verified' => false,
                'reason'   => 'Could not process receipt. Please try again.',
            ];

        } catch (\Exception $e) {
            \Log::error('AI verification error: ' . $e->getMessage());
            return [
                'verified' => false,
                'reason'   => 'Verification service temporarily unavailable.',
            ];
        }
    }

    public function getPaymentStatus($orderId)
    {
        $order = Order::findOrFail($orderId);
        return response()->json([
            'payment_status'   => $order->payment_status,
            'payment_verified' => $order->payment_verified,
            'gcash_ref_no'     => $order->gcash_ref_no,
            'verified_at'      => $order->payment_verified_at,
        ]);
    }
}