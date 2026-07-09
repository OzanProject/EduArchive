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
    $token = AppSetting::getSetting('wa_wablas_token', '');
    $domain = AppSetting::getSetting('wa_wablas_domain', '');

    if (empty($token) || empty($domain)) {
      Log::error("WhatsApp Wablas Token or Domain is missing.");
      return false;
    }

    try {
      $response = Http::withHeaders([
        'Authorization' => $token,
      ])->post(rtrim($domain, '/') . '/api/send-message', [
        'phone' => $phone,
        'message' => $message,
      ]);

      return $response->successful();
    } catch (\Exception $e) {
      Log::error("WhatsApp Wablas Error: " . $e->getMessage());
      return false;
    }
  }

  protected function sendTwilio($phone, $message)
  {
    $sid = AppSetting::getSetting('wa_twilio_sid', '');
    $token = AppSetting::getSetting('wa_twilio_token', '');
    $from = AppSetting::getSetting('wa_twilio_from', '');

    if (empty($sid) || empty($token) || empty($from)) {
      Log::error("WhatsApp Twilio credentials missing.");
      return false;
    }

    // Twilio requires format "whatsapp:+1234567890"
    $to = 'whatsapp:+' . $phone;
    $from = 'whatsapp:' . $from;

    try {
      $response = Http::withBasicAuth($sid, $token)
        ->asForm()
        ->post("https://api.twilio.com/2010-04-01/Accounts/{$sid}/Messages.json", [
          'From' => $from,
          'To' => $to,
          'Body' => $message,
        ]);

      return $response->successful();
    } catch (\Exception $e) {
      Log::error("WhatsApp Twilio Error: " . $e->getMessage());
      return false;
    }
  }
}
