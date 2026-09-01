<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class SchoolMonitoringExport implements FromView, ShouldAutoSize
{
    protected $tenant;
    protected $data;
    protected $status;
    protected $year;
    protected $age_filter;
    protected $required_types;

    public function __construct($tenant, $data, $status, $year, $age_filter, $required_types)
    {
        $this->tenant = $tenant;
        $this->data = $data;
        $this->status = $status;
        $this->year = $year;
        $this->age_filter = $age_filter;
        $this->required_types = $required_types;
    }

    public function view(): View
    {
        $totalSiswa = count($this->data);
        $sudahVerifikasi = 0;
        $belumVerifikasi = 0;

        foreach ($this->data as $student) {
            $approvedTypes = $student->documents->pluck('document_type')->toArray();
            
            $isVerified = true;
            foreach ($this->required_types as $req) {
                if (!in_array($req, $approvedTypes)) {
                    $isVerified = false;
                    break;
                }
            }
            
            if ($isVerified) {
                $sudahVerifikasi++;
                $student->is_verified = true;
            } else {
                $belumVerifikasi++;
                $student->is_verified = false;
            }
        }

        $docTypes = \App\Models\DocumentType::where('is_active', true)->get();

        return view('backend.superadmin.monitoring.export_excel', [
            'tenant' => $this->tenant,
            'data' => $this->data,
            'status' => $this->status,
            'year' => $this->year,
            'age_filter' => $this->age_filter,
            'totalSiswa' => $totalSiswa,
            'sudahVerifikasi' => $sudahVerifikasi,
            'belumVerifikasi' => $belumVerifikasi,
            'docTypes' => $docTypes,
        ]);
    }
}
