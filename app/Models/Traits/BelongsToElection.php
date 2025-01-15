<?php

namespace App\Models\Traits;

use App\Models\Election;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait BelongsToElection
{
    public function scopeElection(Builder $query, Election|int $election): void
    {
        $query->where('election_id', '=', is_int($election) ? $election : $election->id);
    }

    public function election(): BelongsTo
    {
        return $this->belongsTo(Election::class);
    }
}
