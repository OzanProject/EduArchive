<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\AppSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
  public function index()
  {
    return redirect()->route('superadmin.settings.general');
  }

  public function general()
  {
    $settings = AppSetting::all()->pluck('value', 'key');
    return view('backend.superadmin.settings.general', compact('settings'));
  }

  public function landing()
  {
    $settings = AppSetting::all()->pluck('value', 'key');
    return view('backend.superadmin.settings.landing', compact('settings'));
  }

  public function footer()
  {
    $settings = AppSetting::all()->pluck('value', 'key');
    return view('backend.superadmin.settings.footer', compact('settings'));
  }

  public function smtp()
  {
    $settings = AppSetting::all()->pluck('value', 'key');
    return view('backend.superadmin.settings.smtp', compact('settings'));
  }

  public function whatsapp()
  {
    $settings = AppSetting::all()->pluck('value', 'key');
    return view('backend.superadmin.settings.whatsapp', compact('settings'));
  }

  public function testSmtp(Request $request)
  {
      $request->validate([
          'test_email' => 'required|email',
      ]);

      try {
          \Illuminate\Support\Facades\Mail::to($request->test_email)->send(new \App\Mail\TestSmtpMail());

          return back()->with('success', 'Email percobaan berhasil dikirim ke ' . $request->test_email . '. Koneksi SMTP berjalan dengan baik!');
      } catch (\Exception $e) {
          return back()->with('error', 'Gagal mengirim email percobaan. Pastikan pengaturan SMTP sudah DISIMPAN terlebih dahulu dengan benar. Error: ' . $e->getMessage());
      }
  }

  public function update(Request $request)
  {
    // Define file keys to exclude from general text update
    $fileKeys = [
      'app_logo',
      'app_favicon',
      'login_cover_image',
      'landing_hero_image',
      'landing_arch_image',
      'landing_partner_logo_1',
      'landing_partner_logo_2',
      'landing_partner_logo_3',
      'landing_partner_logo_4',
      'landing_partner_logo_5'
    ];

    $data = $request->except(['_token', '_method', ...$fileKeys]);

    // Handle Text Inputs
    foreach ($data as $key => $value) {
      AppSetting::updateOrCreate(
        ['key' => $key],
        ['value' => $value]
      );
    }

    // Handle File Uploads
    foreach ($fileKeys as $key) {
      if ($request->hasFile($key)) {
        $path = $request->file($key)->store('settings', 'public');
        $url = Storage::url($path);

        AppSetting::updateOrCreate(
          ['key' => $key],
          ['value' => $url]
        );
      }
    }

    // Clear All Related Caches
    Cache::forget('app_settings_global');
    Cache::forget('dinas_app_logo');
    Cache::forget('dinas_app_favicon');
    Cache::forget('central_branding');

    // Also clear individual keys just in case other parts use them
    if ($request->has('app_timezone')) {
      Cache::forget("app_setting_app_timezone");
    }

    return back()->with('success', 'Pengaturan berhasil diperbarui.');
  }
}
