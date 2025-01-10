<?php

namespace App\Observers;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AuditLogObserver
{
    public function __construct() {}

    private function log($message, Model $model)
    {
        /** @var User | null $user */
        $user = Auth::user();

        $message = sprintf('%s %s(#%s) by %s(#%s)', $message, class_basename($model), $model->getAttribute('id'), $user?->name ?? 'Unknown user', $user?->id ?? '-');

        Log::channel('audit')->info($message, [
            'user' => $user,
            'model' => $model,
        ]);
    }

    public function created(Model $model): void
    {
        $this->log('Created', $model);
    }

    public function updated(Model $model): void
    {
        $this->log('Updated', $model);
    }

    public function deleted(Model $model): void
    {
        $this->log('Deleted', $model);
    }

    public function restored(Model $model): void
    {
        $this->log('Restored', $model);
    }

    public function forceDeleted(Model $model): void
    {
        $this->log('Force deleted', $model);
    }
}
