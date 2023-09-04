<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BusinessesSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    DB::table('businesses')->insert([
      [
        'name' => 'Business 1',
        'city' => 'City 1',
        'created_at' => now(),
        'updated_at' => now(),
      ],
    ]);
  }
}
