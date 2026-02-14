<?php

namespace App\Tenancy\Bootstrappers;

use Stancl\Tenancy\Contracts\TenancyBootstrapper;
use Stancl\Tenancy\Contracts\Tenant;

class CustomBootstrapper implements TenancyBootstrapper
{
  public function bootstrap(Tenant $tenant)
  {
    // Custom logic when tenant is bootstrapped
  }

  public function revert()
  {
    // Custom logic when tenant is reverted
  }
}
