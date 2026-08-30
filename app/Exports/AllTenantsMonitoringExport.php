<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class AllTenantsMonitoringExport implements FromView, ShouldAutoSize, WithStyles
{
    protected $tenants;
    protected $category;

    public function __construct($tenants, $category)
    {
        $this->tenants = $tenants;
        $this->category = $category;
    }

    public function view(): View
    {
        return view('backend.superadmin.monitoring.export_all_excel', [
            'tenants' => $this->tenants,
            'category' => $this->category
        ]);
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
