<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class WilayahController extends Controller
{
    /**
     * Base URL for wilayah data source.
     * Server-to-server request — tidak terkena CORS.
     */
    protected string $baseUrl = 'https://emsifa.github.io/api-wilayah-indonesia/api';

    /**
     * Cache duration: 7 hari (data wilayah jarang berubah)
     */
    protected int $cacheTtl = 60 * 60 * 24 * 7;

    /**
     * Daftar seluruh provinsi Indonesia.
     */
    public function provinces()
    {
        $data = Cache::remember('wilayah_provinces', $this->cacheTtl, function () {
            $response = Http::timeout(15)->get("{$this->baseUrl}/provinces.json");
            return $response->successful() ? $response->json() : [];
        });

        return response()->json($data)
            ->header('Access-Control-Allow-Origin', '*')
            ->header('Cache-Control', 'public, max-age=86400');
    }

    /**
     * Daftar kabupaten/kota berdasarkan kode provinsi.
     */
    public function regencies(string $provinceCode)
    {
        $cacheKey = "wilayah_regencies_{$provinceCode}";
        $data = Cache::remember($cacheKey, $this->cacheTtl, function () use ($provinceCode) {
            $response = Http::timeout(15)->get("{$this->baseUrl}/regencies/{$provinceCode}.json");
            return $response->successful() ? $response->json() : [];
        });

        return response()->json($data)
            ->header('Access-Control-Allow-Origin', '*')
            ->header('Cache-Control', 'public, max-age=86400');
    }

    /**
     * Daftar kecamatan berdasarkan kode kabupaten/kota.
     */
    public function districts(string $regencyCode)
    {
        $cacheKey = "wilayah_districts_{$regencyCode}";
        $data = Cache::remember($cacheKey, $this->cacheTtl, function () use ($regencyCode) {
            $response = Http::timeout(15)->get("{$this->baseUrl}/districts/{$regencyCode}.json");
            return $response->successful() ? $response->json() : [];
        });

        return response()->json($data)
            ->header('Access-Control-Allow-Origin', '*')
            ->header('Cache-Control', 'public, max-age=86400');
    }

    /**
     * Daftar desa/kelurahan berdasarkan kode kecamatan.
     */
    public function villages(string $districtCode)
    {
        $cacheKey = "wilayah_villages_{$districtCode}";
        $data = Cache::remember($cacheKey, $this->cacheTtl, function () use ($districtCode) {
            $response = Http::timeout(15)->get("{$this->baseUrl}/villages/{$districtCode}.json");
            return $response->successful() ? $response->json() : [];
        });

        return response()->json($data)
            ->header('Access-Control-Allow-Origin', '*')
            ->header('Cache-Control', 'public, max-age=86400');
    }
}
