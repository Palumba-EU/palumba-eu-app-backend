<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Collection;

/**
 * @property string $uuid
 * @property Carbon|null $created_at
 * @property int|null $age
 * @property int $country_id
 * @property string $language_code
 * @property string|null $gender
 * @property Country $country
 * @property Collection<Statement> $statements
 * @property string|null $hashed_ip_address
 */
class Response extends Model
{
    use HasFactory, HasUuids;

    public $primaryKey = 'uuid';

    public $timestamps = false;

    protected $casts = ['created_at' => 'datetime'];

    protected $fillable = ['created_at', 'age', 'country_id', 'language_code', 'gender', 'hashed_ip_address'];

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    public function statements(): BelongsToMany
    {
        return $this->belongsToMany(Statement::class)->withPivot('answer');
    }

    public function scopeSince(Builder $query, Carbon $date): void
    {
        $query
            ->whereDate('created_at', '>=', $date)
            // Include null values, because due to anonymization, the latest requests will not have a timestamp until
            // the anonymization batch size is reached.
            ->orWhereNull('created_at');
    }
}
