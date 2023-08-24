<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class SuppliersSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    DB::table('suppliers')->insert([
      [
        'name' => 'Supplier 1',
        'city' => 'City 1',
        'category' => 'Category 1',
        'created_at' => now(),
        'updated_at' => now(),
      ],
      [
        'name' => 'Supplier 2',
        'city' => 'City 2',
        'category' => 'Category 2',
        'created_at' => now(),
        'updated_at' => now(),
      ],
      [
        'name' => 'Supplier 3',
        'city' => 'City 3',
        'category' => 'Category 3',
        'created_at' => now(),
        'updated_at' => now(),
      ],
    ]);
  }
}

