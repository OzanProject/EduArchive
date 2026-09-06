<?php

namespace App\Exports;

use App\Models\Tenant;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class TenantsExport implements FromCollection, WithHeadings, WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Tenant::latest()->get();
    }

    public function headings(): array
    {
        return [
            'ID / NPSN',
            'Domain',
            'Nama Sekolah',
            'Jenjang',
            'Status Aktif',
            'Tanggal Daftar',
        ];
    }

    public function map($tenant): array
    {
        return [
            $tenant->npsn,
            $tenant->id,
            $tenant->nama_sekolah,
            $tenant->jenjang,
            $tenant->status_aktif ? 'Aktif' : 'Belum Diverifikasi',
            $tenant->created_at ? $tenant->created_at->format('d/m/Y H:i') : '-',
        ];
    }
}
