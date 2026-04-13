<?php

namespace App\Http\Controllers;

use App\Models\Otp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class OtpController extends Controller
{
    // -----------------------------------------------
    // SEND — generates OTP and emails it to the user
    // -----------------------------------------------
    public function send(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        // Generate a random 6-digit OTP
        $otp = strval(rand(100000, 999999));

        // Delete any existing OTP for this email
        Otp::where('email', $request->email)->delete();

        // Save the new OTP to the database
        Otp::create([
            'email'      => $request->email,
            'otp'        => $otp,
            'expires_at' => now()->addMinutes(10),
        ]);

        // Send the OTP via email
        Mail::raw("Your Online Bagsakan OTP is: $otp. It expires in 10 minutes.", function ($message) use ($request, $otp) {
            $message->to($request->email)
                    ->subject('Your Online Bagsakan OTP');
        });

        return response()->json([
            'message' => 'OTP sent to your email!',
        ]);
    }

    // -----------------------------------------------
    // VERIFY — checks if the OTP is correct
    // -----------------------------------------------
    public function verify(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'code'   => 'required|string',
        ]);

        // Find the OTP in the database
        $otpRecord = Otp::where('email', $request->email)
            ->where('otp', $request->code)
            ->first();

        // Check if OTP exists
        if (!$otpRecord) {
            return response()->json([
                'message' => 'Invalid OTP.',
            ], 400);
        }

        // Check if OTP is expired
        if (now()->isAfter($otpRecord->expires_at)) {
            $otpRecord->delete();
            return response()->json([
                'message' => 'OTP has expired. Please request a new one.',
            ], 400);
        }

        // OTP is valid — delete it so it can't be used again
        $otpRecord->delete();

        return response()->json([
            'message' => 'OTP verified successfully!',
        ]);
    }
}