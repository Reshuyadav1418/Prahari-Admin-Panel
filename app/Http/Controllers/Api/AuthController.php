<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\SmsService;
use App\Http\Requests\Api\SendOtpRequest;
use App\Http\Requests\Api\VerifyOtpRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AuthController extends Controller
{
    protected $smsService;

    public function __construct(SmsService $smsService)
    {
        $this->smsService = $smsService;
    }

    /**
     * Get Connection ID (Initial Handshake)
     */
    public function getConnectionId()
    {
        $connectionId = uniqid('conn_');
        
        // Store connection ID in cache for 10 minutes
        \Illuminate\Support\Facades\Cache::put('connection_' . $connectionId, true, now()->addMinutes(10));

        return response()->json([
            'status' => true,
            'message' => 'Connection established',
            'data' => ['connection_id' => $connectionId]
        ]);
    }

    /**
     * Send OTP to the user's phone.
     */
    public function requestOtp(SendOtpRequest $request)
    {
        // Verify Connection ID
        if (!\Illuminate\Support\Facades\Cache::has('connection_' . $request->connection_id)) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid or expired connection ID. Please handshake again.',
                'errors' => []
            ], 403);
        }

        $phone = $request->phone;
        $otp = rand(100000, 999999);
        $expiry = Carbon::now()->addMinutes(5);

        // Find or create user
        $user = User::where('phone', $phone)->first();

        if (!$user) {
            $user = User::create([
                'phone' => $phone,
                'name' => $request->name,
                'role' => 'prahari',
                'is_active' => false
            ]);
        } else if ($request->has('name')) {
            $user->update(['name' => $request->name]);
        }

        // Update OTP and expiry
        $user->update([
            'otp' => $otp,
            'otp_expire_at' => $expiry
        ]);

        // Send OTP via SMS service
        $smsData = (object) [
            'mobile' => $phone,
            'otp' => $otp
        ];

        $smsSent = $this->smsService->sendSmsOtp($smsData);

        if ($smsSent) {
            return response()->json([
                'status' => true,
                'message' => 'OTP sent successfully',
                'data' => []
            ], 200);
        }

        return response()->json([
            'status' => false,
            'message' => 'Failed to send OTP. Please try again later.',
            'errors' => []
        ], 500);
    }

    /**
     * Verify OTP and login/register user.
     */
    public function verifyOtp(VerifyOtpRequest $request)
    {
        $user = User::where('phone', $request->phone)
                    ->where('otp', $request->otp)
                    ->first();

        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid OTP or phone number',
                'errors' => []
            ], 401);
        }

        if (Carbon::now()->gt($user->otp_expire_at)) {
            return response()->json([
                'status' => false,
                'message' => 'OTP has expired',
                'errors' => []
            ], 401);
        }

        // OTP verified successfully
        $user->update([
            'otp' => null,
            'otp_expire_at' => null,
            'is_active' => true
        ]);

        // Generate Sanctum token
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'status' => true,
            'message' => 'Login successful',
            'data' => [
                'access_token' => $token,
                'token_type' => 'Bearer',
                'user' => $user
            ]
        ], 200);
    }

    /**
     * Resend OTP.
     */
    public function resendOtp(SendOtpRequest $request)
    {
        return $this->requestOtp($request);
    }

    /**
     * Logout authenticated user.
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'status' => true,
            'message' => 'Logged out successfully',
            'data' => []
        ], 200);
    }

    /**
     * Get authenticated user profile.
     */
    public function profile(Request $request)
    {
        return response()->json([
            'status' => true,
            'message' => 'Profile retrieved successfully',
            'data' => [
                'user' => $request->user()
            ]
        ], 200);
    }
}
