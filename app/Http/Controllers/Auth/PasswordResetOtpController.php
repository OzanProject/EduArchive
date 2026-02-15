<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\WhatsAppService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Carbon\Carbon;

class PasswordResetOtpController extends Controller
{
  protected $whatsappService;

  public function __construct(WhatsAppService $whatsappService)
  {
    $this->whatsappService = $whatsappService;
  }

  // Handle sending OTP
  public function sendOtp(Request $request)
  {
    $request->validate([
      'wa_number' => 'required|numeric'
    ]);

    $waNumber = $request->wa_number;

    // Try to find user by wa_number (simple match for now)
    // In a real app, you might want to normalize numbers (e.g. 08xx -> 628xx) before searching
    $user = User::where('wa_number', $waNumber)->first();

    if (!$user) {
      // Try searching with 62 prefix if user entered 0
      if (substr($waNumber, 0, 1) === '0') {
        $formatted = '62' . substr($waNumber, 1);
        $user = User::where('wa_number', $formatted)->first();
      }
    }

    if (!$user) {
      return response()->json(['message' => 'Nomor WhatsApp tidak ditemukan.'], 404);
    }

    // Generate OTP
    $otp = rand(100000, 999999);
    $user->otp = Hash::make($otp); // Hash OTP for security
    $user->otp_expires_at = Carbon::now()->addMinutes(10);
    $user->save();

    // Send OTP via WhatsApp
    $message = "Kode OTP Reset Password Anda adalah: *{$otp}*.\nKode ini berlaku selama 10 menit. Jangan berikan kepada siapa pun.";

    // Use the number from DB or Request? Use DB to ensure it works with the service
    $targetNumber = $user->wa_number;

    if ($this->whatsappService->send($targetNumber, $message)) {
      return response()->json(['message' => 'OTP berhasil dikirim ke WhatsApp Anda.', 'phone' => $this->maskPhone($targetNumber)]);
    } else {
      return response()->json(['message' => 'Gagal mengirim OTP. Silakan coba lagi nanti.'], 500);
    }
  }

  // Verify OTP
  public function verifyOtp(Request $request)
  {
    $request->validate([
      'wa_number' => 'required',
      'otp' => 'required|numeric|digits:6'
    ]);

    // Find user logic (same as sendOtp, extract to method if needed)
    $waNumber = $request->wa_number;
    $user = User::where('wa_number', $waNumber)->first();
    if (!$user && substr($waNumber, 0, 1) === '0') {
      $user = User::where('wa_number', '62' . substr($waNumber, 1))->first();
    }

    if (!$user || !$user->otp || Hash::check($request->otp, $user->otp) === false) {
      return response()->json(['message' => 'Kode OTP salah atau tidak valid.'], 400);
    }

    if (Carbon::now()->greaterThan($user->otp_expires_at)) {
      return response()->json(['message' => 'Kode OTP telah kedaluwarsa.'], 400);
    }

    return response()->json(['message' => 'OTP Valid.', 'valid' => true]);
  }

  // Reset Password with OTP
  public function resetPassword(Request $request)
  {
    $request->validate([
      'wa_number' => 'required',
      'otp' => 'required|numeric|digits:6',
      'password' => 'required|confirmed|min:8',
    ]);

    $waNumber = $request->wa_number;
    $user = User::where('wa_number', $waNumber)->first();
    if (!$user && substr($waNumber, 0, 1) === '0') {
      $user = User::where('wa_number', '62' . substr($waNumber, 1))->first();
    }

    // Re-verify OTP to prevent bypass
    if (!$user || !$user->otp || Hash::check($request->otp, $user->otp) === false || Carbon::now()->greaterThan($user->otp_expires_at)) {
      return response()->json(['message' => 'Sesi tidak valid. Silakan ulangi proses OTP.'], 400);
    }

    // Update Password
    $user->password = Hash::make($request->password);
    $user->otp = null; // Clear OTP
    $user->otp_expires_at = null;
    $user->save();

    return response()->json(['message' => 'Password berhasil diubah. Silakan login.']);
  }

  private function maskPhone($phone)
  {
    if (strlen($phone) <= 4)
      return $phone;
    return substr($phone, 0, 2) . '*****' . substr($phone, -2);
  }
}
