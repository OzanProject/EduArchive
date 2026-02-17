<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class GuideController extends Controller
{
  /**
   * Display the website guide page.
   */
  public function index()
  {
    $userRole = auth()->user()->role;
    $prefix = $userRole === 'admin_sekolah' ? 'adminlembaga.' : 'operator.';
    return view('backend.guide.index', compact('userRole', 'prefix'));
  }
}
