<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ApiTokenController extends Controller
{
    public function index()
    {
        $tenant = tenant();
        $tokens = $tenant->tokens()->latest()->get();

        return view('backend.adminlembaga.api_tokens.index', compact('tokens'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'token_name' => 'required|string|max:255',
        ]);

        $tenant = tenant();
        // Create token with abilities for reading data
        $token = $tenant->createToken($request->token_name, ['read']);

        return redirect()->route('adminlembaga.api_tokens.index', tenant('id'))
            ->with('success', 'Token berhasil dibuat. Simpan key berikut baik-baik karena hanya akan ditampilkan sekali: ' . $token->plainTextToken);
    }

    public function destroy($tokenId)
    {
        $tenant = tenant();
        $tenant->tokens()->where('id', $tokenId)->delete();

        return redirect()->route('adminlembaga.api_tokens.index', tenant('id'))
            ->with('success', 'Token API berhasil dihapus.');
    }
}
