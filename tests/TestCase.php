<?php

namespace Tests;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
  use CreatesApplication;

  protected function initDatabase(): void
  {

    Artisan::call('migrate --seed');
    Artisan::call('app:user-examples-init');
  }

  protected function resetDatabase(): void
  {
    Artisan::call('migrate:reset');
  }
}
