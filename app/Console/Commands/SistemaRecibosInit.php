<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class SistemaRecibosInit extends Command
{
  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'app:sistema-recibos-init';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Inicializa el sistema de recibos con usuarios, roles, permisos, etc.';

  /**
   * Execute the console command.
   */
  public function handle()
  {
    $this->info('Inicializando el sistema de recibos...');
    $this->call('db:seed', [
      'class' => 'SistemaRecibosSeeder',
    ]);
    $this->call('permission:create-role', ['name' => 'super-admin', 'guard' => 'api']);
    $this->call('permission:create-role', [
      'name' => 'business-manager',
      'guard' => 'api',
      'permissions' => "edit business|create projects|edit projects|delete projects|create items|edit items|delete items|create receipts|edit receipts|delete receipts|create suppliers|edit suppliers|delete suppliers|assign items"
    ]);
    $this->call('permission:create-role', [
      'name' => 'project-manager',
      'guard' => 'api',
      'permissions' => "edit projects|create items|edit items|delete items|create receipts|edit receipts|delete receipts|create suppliers|edit suppliers|delete suppliers|assign items"
    ]);
    $this->call('permission:create-role', [
      'name' => 'executor',
      'guard' => 'api',
      'permissions' => "create items|edit items|delete items|create receipts|edit receipts|delete receipts|create suppliers|edit suppliers|delete suppliers"
    ]);

    // Asignamos roles
    $superadmin = User::where('email', '=', 'superadmin@test.com')->first();
    $superadmin->assignRole('super-admin');
    $businessManager = User::where('email', '=', 'businessm@test.com')->first();
    $businessManager->assignRole('business-manager');
    $projectManager = User::where('email', '=', 'projectm@test.com')->first();
    $projectManager->assignRole('project-manager');
    $executor = User::where('email', '=', 'executor@test.com')->first();
    $executor->assignRole('executor');

    $this->info('Sistema de recibos inicializado con éxito!');

  }
}
