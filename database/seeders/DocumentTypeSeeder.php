<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DocumentTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = [
            ['name' => 'Ijazah', 'code' => 'IJAZAH', 'is_required' => true],
            ['name' => 'Kartu Keluarga', 'code' => 'KK', 'is_required' => true],
            ['name' => 'KTP', 'code' => 'KTP', 'is_required' => false], // Siswa mungkin belum punya KTP
            ['name' => 'Akte Kelahiran', 'code' => 'AKTE', 'is_required' => true],
            ['name' => 'Raport', 'code' => 'RAPORT', 'is_required' => false],
            ['name' => 'Sertifikat', 'code' => 'SERTIFIKAT', 'is_required' => false],
        ];

        foreach ($types as $type) {
            \App\Models\DocumentType::firstOrCreate(
                ['code' => $type['code']],
                $type
            );
        }
    }
}
