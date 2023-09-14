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

    $globalPermissions = [
      'view businesses', //listado
      'view business', //singular
      'create business',
      'delete business',
    ];

    $businessManagerPermissions = [
      'view business',
      'edit business',
      'view projects',
      'view project',
      'create project',
      'edit project',
      'delete project',
      'view items',
      'view item',
      'create item',
      'edit item',
      'delete item',
      'view receipts',
      'view receipt',
      'create receipt',
      'edit receipt',
      'delete receipt',
      'view suppliers',
      'view supplier',
      'create supplier',
      'edit supplier',
      'delete supplier',
      'assign items',
    ];
    $this->call('permission:create-role', [
      'name' => 'business-manager',
      'guard' => 'api',
      'permissions' => implode('|', $businessManagerPermissions),
    ]);
    $projectManagerPermissions = [
      'view projects',
      'view project',
      'edit project',
      'view items',
      'view item',
      'create item',
      'edit item',
      'delete item',
      'view receipts',
      'view receipt',
      'create receipt',
      'edit receipt',
      'delete receipt',
      'view suppliers',
      'view supplier',
      'create supplier',
      'edit supplier',
      'delete supplier',
      'assign items',
    ];
    $this->call('permission:create-role', [
      'name' => 'project-manager',
      'guard' => 'api',
      'permissions' => implode('|', $projectManagerPermissions),
    ]);
    $executorPermissions = [
      'view items',
      'view item',
      'create item',
      'edit item',
      'delete item',
      'view receipts',
      'view receipt',
      'create receipt',
      'edit receipt',
      'delete receipt',
      'view suppliers',
      'view supplier',
      'create supplier',
      'edit supplier',
      'delete supplier',
    ];
    $this->call('permission:create-role', [
      'name' => 'executor',
      'guard' => 'api',
      'permissions' => implode('|', $executorPermissions),
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
