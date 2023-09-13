<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class SistemaRecibosSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    $this->call([
      SuppliersSeeder::class,
      BusinessesSeeder::class,
    ]);
    // super admin del sistema
    DB::table('users')->insert([
      'name' => 'Super Admin',
      'email' => 'superadmin@test.com',
      'password' => Hash::make('password'),
      'created_at' => now(),
      'updated_at' => now(),
    ]);

    // business manager
    DB::table('users')->insert([
      'name' => 'Business Manager',
      'email' => 'businessm@test.com',
      'password' => Hash::make('password'),
      'created_at' => now(),
      'updated_at' => now(),
    ]);

    // project manager
    DB::table('users')->insert([
      'name' => 'Project Manager',
      'email' => 'projectm@test.com',
      'password' => Hash::make('password'),
      'created_at' => now(),
      'updated_at' => now(),
    ]);

    // executor
    DB::table('users')->insert([
      'name' => 'Executor',
      'email' => 'executor@test.com',
      'password' => Hash::make('password'),
      'created_at' => now(),
      'updated_at' => now(),
    ]);
  }
}

