<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AppSetting;

class FrontendController extends Controller
{
  public function index()
  {
    $settings = AppSetting::all()->pluck('value', 'key')->toArray();

    // Logic synced with login.blade.php
    $dinas_logo = \Illuminate\Support\Facades\Cache::get('dinas_app_logo');
    $defaultLogo = asset('adminlte/dist/img/AdminLTELogo.png');

    // Priority: Landing Logo > Cache (Dinas) > App Logo DB > Default
    if (isset($settings['landing_logo']) && $settings['landing_logo']) {
      $logo = asset($settings['landing_logo']);
    } elseif ($dinas_logo) {
      $logo = $dinas_logo;
    } elseif (isset($settings['app_logo']) && $settings['app_logo']) {
      $logo = asset($settings['app_logo']);
    } else {
      $logo = $defaultLogo;
    }

    // Favicon Logic
    $favicon = null;
    if (isset($settings['app_favicon']) && $settings['app_favicon']) {
      $favicon = asset($settings['app_favicon']);
    } elseif ($dinas_logo) {
      $favicon = $dinas_logo; // Use Cache Dinas Logo as fallback
    } elseif (isset($settings['app_logo']) && $settings['app_logo']) {
      $favicon = asset($settings['app_logo']);
    } else {
      $favicon = $defaultLogo;
    }

    return view('frontend.index', compact('settings', 'logo', 'favicon'));
  }
}
