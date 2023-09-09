<?php
/**
 * Created by Eressea Solutions
 * @author Rene Silva <rsilva@eresseasolutions.com>
 */

namespace Eressea\MelianTaxonomy;

use Illuminate\Support\ServiceProvider;

class MelianTaxonomyServiceProvider extends ServiceProvider
{
  public function register()
  {

  }

  public function boot()
  {
    $this->loadRoutesFrom(__DIR__ . '/../routes/api.php');
    $this->loadViewsFrom(__DIR__ . '/../resources/views', 'melian-taxonomy');
    $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
  }
}
