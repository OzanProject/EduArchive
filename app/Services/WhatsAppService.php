<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\AppSetting;

class WhatsAppService
{
  protected $provider;
  protected $apiKey;
  protected $endpoint;

  public function __construct()
  {
    // Load settings from DB (AppSetting)
    $this->provider = AppSetting::getSetting('wa_provider', 'log'); // Default to log
    $this->apiKey = AppSetting::getSetting('wa_api_key', '');
    $this->endpoint = AppSetting::getSetting('wa_endpoint', '');
  }

  /**
   * Send WhatsApp message.
   *
   * @param string $phone
   * @param string $message
   * @return bool
   */
  public function send(string $phone, string $message): bool
  {
    // Remove non-numeric characters from phone
    $phone = preg_replace('/[^0-9]/', '', $phone);

    // Ensure standard format (e.g., 628...)
    if (substr($phone, 0, 1) === '0') {
      $phone = '62' . substr($phone, 1);
    }

    switch ($this->provider) {
      case 'fonnte':
        return $this->sendFonnte($phone, $message);
      case 'wablas':
        return $this->sendWablas($phone, $message);
      case 'twilio':
        return $this->sendTwilio($phone, $message);
      default:
        return $this->sendLog($phone, $message);
    }
  }

  protected function sendLog($phone, $message)
  {
    Log::info("[WhatsApp-Log] To: {$phone} | Message: {$message}");
    return true;
  }

  protected function sendFonnte($phone, $message)
  {
    if (empty($this->apiKey)) {
      Log::error("WhatsApp Fonnte API Key is missing.");
      return false;
    }

    try {
      $response = Http::withHeaders([
        'Authorization' => $this->apiKey,
      ])->post('https://api.fonnte.com/send', [
            'target' => $phone,
            'message' => $message,
          ]);

      return $response->successful();
    } catch (\Exception $e) {
      Log::error("WhatsApp Fonnte Error: " . $e->getMessage());
      return false;
    }
  }

  protected function sendWablas($phone, $message)
  {
    // Placeholder for Wablas implementation
    Log::warning("WhatsApp Wablas provider not fully implemented yet.");
    return false;
  }

  protected function sendTwilio($phone, $message)
  {
    // Placeholder for Twilio
    Log::warning("WhatsApp Twilio provider not fully implemented yet.");
    return false;
  }
}
