<?php

namespace App\Listeners;

use Illuminate\Support\Facades\URL;
use Stancl\Tenancy\Events\InitializingTenancy;

class SetTenantUrlParameter
{
  /**
   * Handle the event.
   *
   * @param  InitializingTenancy  $event
   * @return void
   */
  public function handle(InitializingTenancy $event)
  {
    URL::defaults(['tenant' => $event->tenancy->tenant->getTenantKey()]);
  }
}
