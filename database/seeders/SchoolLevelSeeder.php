<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SchoolLevelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $levels = [
            [
                'name' => 'SD',
                'description' => 'Sekolah Dasar / Madrasah Ibtidaiyah',
                'sequence' => 1,
            ],
            [
                'name' => 'SMP',
                'description' => 'Sekolah Menengah Pertama / Madrasah Tsanawiyah',
                'sequence' => 2,
            ],
            [
                'name' => 'SMA',
                'description' => 'Sekolah Menengah Atas / Madrasah Aliyah',
                'sequence' => 3,
            ],
            [
                'name' => 'SMK',
                'description' => 'Sekolah Menengah Kejuruan',
                'sequence' => 4,
            ],
        ];

        foreach ($levels as $level) {
            \App\Models\SchoolLevel::updateOrCreate(
                ['name' => $level['name']],
                $level
            );
        }
    }
}
