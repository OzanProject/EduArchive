<?php

namespace App\Listeners;

use Illuminate\Support\Facades\URL;
use Stancl\Tenancy\Events\EndingTenancy;

class ClearTenantUrlParameter
{
  /**
   * Handle the event.
   *
   * @param  EndingTenancy  $event
   * @return void
   */
  public function handle(EndingTenancy $event)
  {
    // Remove 'tenant' from defaults
    $defaults = URL::getDefaultParameters();
    unset($defaults['tenant']);
    URL::defaults($defaults);
  }
}
