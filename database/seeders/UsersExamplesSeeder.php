<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersExamplesSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    // Super Admin
    DB::table('users')->insert([
      'name' => 'Super Admin',
      'email' => 'superadmin@test.com',
      'password' => Hash::make('password'),
      'created_at' => now(),
      'updated_at' => now(),
    ]);

    // Test user para revisar funcionalidad una vez iniciada la sesión
    DB::table('users')->insert([
      'name' => 'Test User',
      'email' => 'test@test.com',
      'password' => Hash::make('password'),
      'created_at' => now(),
      'updated_at' => now(),
    ]);
  }
}
