<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\AppSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class SchoolSettingController extends Controller
{
  public function index()
  {
    $settings = AppSetting::all()->pluck('value', 'key');

    // Merge defaults from Tenant model if not present in AppSettings
    if (!isset($settings['school_name']) && tenant('nama_sekolah')) {
      $settings['school_name'] = tenant('nama_sekolah');
    }

    if (!isset($settings['school_logo']) && tenant('logo')) {
      $settings['school_logo'] = tenant_asset(tenant('logo'));
    }

    $tenant = tenant();

    // Fetch Global Support Phone from Central Database
    try {
      $supportPhone = \Illuminate\Support\Facades\DB::connection(config('tenancy.database.central_connection'))
        ->table('app_settings')
        ->where('key', 'app_support_phone')
        ->value('value');
    } catch (\Exception $e) {
      $supportPhone = null;
    }

    return view('backend.adminlembaga.settings.index', compact('settings', 'tenant', 'supportPhone'));
  }

  public function editProfile()
  {
    $settings = AppSetting::all()->pluck('value', 'key');
    // Merge defaults
    if (!isset($settings['school_name']) && tenant('nama_sekolah')) {
      $settings['school_name'] = tenant('nama_sekolah');
    }
    $tenant = tenant();
    return view('backend.adminlembaga.settings.profile', compact('settings', 'tenant'));
  }

  public function update(Request $request)
  {
    // Logs removed for production

    $data = $request->except(['_token', '_method', 'school_logo', 'logo_kabupaten', 'school_signature', 'school_stamp', 'school_headmaster_photo']);

    // Clear the global array cache for this tenant
    Cache::forget('app_settings_' . (tenant('id') ?? 'global'));

    // Handle Text Inputs
    foreach ($data as $key => $value) {
      AppSetting::updateOrCreate(
        ['key' => $key],
        ['value' => $value]
      );

      // Clear specific cache
      $tenantId = tenant('id') ?? 'global';
      Cache::forget("tenant_{$tenantId}_app_setting_{$key}");

      // Sync School Name to Tenant Record
      if ($key === 'school_name') {
        tenant()->update(['nama_sekolah' => $value]);
      }
    }

    // Handle File Uploads (School Logo)
    if ($request->hasFile('school_logo')) {
      $path = $request->file('school_logo')->store('tenant_logos', 'public'); // Store in strict folder

      // Update Tenant Model (Central DB)
      tenant()->update([
        'logo' => $path
      ]);

      // Keep AppSetting for legacy/other uses, but use relative path or full URL as needed
      // tenant_asset() is good for display, but for storage in DB, relative path is often better if using asset helper later
      $url = tenant_asset($path);

      AppSetting::updateOrCreate(
        ['key' => 'school_logo'],
        ['value' => $url, 'type' => 'image']
      );
    }

    // Handle Logo Kabupaten (Left)
    if ($request->hasFile('logo_kabupaten')) {
      $path = $request->file('logo_kabupaten')->store('tenant_logos', 'public');
      $url = tenant_asset($path); // Store absolute URL or relative path handled by accessor

      AppSetting::updateOrCreate(
        ['key' => 'logo_kabupaten'],
        ['value' => $url, 'type' => 'image']
      );
    }

    // Handle File Uploads (Signature)
    if ($request->hasFile('school_signature')) {
      $path = $request->file('school_signature')->store('settings', 'public');
      $url = tenant_asset($path);

      AppSetting::updateOrCreate(
        ['key' => 'school_signature'],
        ['value' => $url, 'type' => 'image']
      );
    }

    // Handle File Uploads (Stamp)
    if ($request->hasFile('school_stamp')) {
      $path = $request->file('school_stamp')->store('settings', 'public');
      $url = tenant_asset($path);

      AppSetting::updateOrCreate(
        ['key' => 'school_stamp'],
        ['value' => $url, 'type' => 'image']
      );
    }

    // Handle File Uploads (Headmaster Photo)
    if ($request->hasFile('school_headmaster_photo')) {
      $path = $request->file('school_headmaster_photo')->store('settings', 'public');
      $url = tenant_asset($path);

      AppSetting::updateOrCreate(
        ['key' => 'school_headmaster_photo'],
        ['value' => $url, 'type' => 'image']
      );
    }

    // Handle File Uploads (Hero Image)
    if ($request->hasFile('school_hero_image')) {
      $path = $request->file('school_hero_image')->store('settings', 'public');
      $url = tenant_asset($path);

      AppSetting::updateOrCreate(
        ['key' => 'school_hero_image'],
        ['value' => $url, 'type' => 'image']
      );
    }

    return back()->with('success', 'Pengaturan sekolah berhasil diperbarui.');
  }
}
