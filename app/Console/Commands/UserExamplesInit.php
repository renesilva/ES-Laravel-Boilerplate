<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class UserExamplesInit extends Command
{
  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'app:user-examples-init';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Inicializa un sistema de ejemplos con usuarios Super Admin (administra usuarios) y Admin';

  /**
   * Execute the console command.
   */
  public function handle(): void
  {
    $this->info('Inicializando el sistema de ejemplos ...');
    $this->call('db:seed', [
      'class' => 'UsersExamplesSeeder',
    ]);
    $this->call('permission:create-role', ['name' => 'super-admin', 'guard' => 'api']);


    $this->call('permission:create-role', [
      'name' => 'admin',
      'guard' => 'api',
      'permissions' => implode('|', []),
    ]);

    // Asignamos roles
    $superadmin = User::where('email', '=', 'superadmin@test.com')->first();
    $superadmin->assignRole('super-admin');
    $businessManager = User::where('email', '=', 'test@test.com')->first();
    $businessManager->assignRole('admin');

    $this->info('Sistema de ejemplo inicializado con éxito!');
  }
}
