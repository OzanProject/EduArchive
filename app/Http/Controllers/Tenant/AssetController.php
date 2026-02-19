<?php

declare(strict_types=1);

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AssetController extends Controller
{
  /**
   * Serve a tenant asset.
   */
  public function asset($path = null)
  {
    if (!$path) {
      abort(404);
    }

    try {
      // In tenant context, storage_path() matches the tenant storage folder
      $filePath = storage_path('app/public/' . $path);

      if (file_exists($filePath)) {
        return response()->file($filePath);
      }

      abort(404);
    } catch (\Throwable $e) {
      abort(404);
    }
  }
}
