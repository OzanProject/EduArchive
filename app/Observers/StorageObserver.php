<?php

namespace App\Observers;

use App\Models\StorageUsage;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class StorageObserver
{
  /**
   * Handle the Model "created" event.
   */
  public function created(Model $model): void
  {
    if (isset($model->file_size)) {
      $this->updateUsage($model->file_size);
    }
  }

  /**
   * Handle the Model "deleted" event.
   */
  public function deleted(Model $model): void
  {
    if (isset($model->file_size)) {
      $this->updateUsage(-$model->file_size);
    }
  }

  protected function updateUsage($bytes)
  {
    try {
      $usage = StorageUsage::firstOrCreate([], ['used_space' => 0]);
      $usage->increment('used_space', $bytes);
      $usage->touch('last_calculated');
    } catch (\Exception $e) {
      Log::error('Storage usage update failed: ' . $e->getMessage());
    }
  }
}
