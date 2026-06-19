<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$tenant = App\Models\Tenant::first();
tenancy()->initialize($tenant);
$students = App\Models\Student::with(['classroom' => function($q) { $q->select('id', 'name'); }])->paginate(10);
echo 'Success';
