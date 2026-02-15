<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Page;
use Illuminate\Support\Str;

class DefaultPagesSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    $pages = [
      [
        'title' => 'Privacy Policy',
        'slug' => 'privacy-policy',
        'content' => '<h1>Privacy Policy</h1><p>This is a default privacy policy. Please update it in the admin panel.</p>',
        'is_published' => true,
      ],
      [
        'title' => 'Terms of Service',
        'slug' => 'terms-of-service',
        'content' => '<h1>Terms of Service</h1><p>This is a default terms of service. Please update it in the admin panel.</p>',
        'is_published' => true,
      ],
    ];

    foreach ($pages as $page) {
      Page::updateOrCreate(
        ['slug' => $page['slug']],
        $page
      );
    }
  }
}
