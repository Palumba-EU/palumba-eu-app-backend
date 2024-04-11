<?php

namespace App\Models\Traits;

use Illuminate\Database\Eloquent\Builder;

trait Publishable
{
    public function scopePublished(Builder $query): void
    {
        $query->where('published', '=', true);
    }
}
