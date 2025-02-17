<?php

namespace App\Models;

use App\Models\Enums\LevelOfEducation;
use App\Models\Traits\BelongsToElection;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Collection;

/**
 * @property string $uuid
 * @property Carbon|null $created_at
 * @property int|null $age
 * @property int $election_id
 * @property Election $election
 * @property int $country_id
 * @property string $language_code
 * @property string|null $gender
 * @property Country $country
 * @property Collection<Statement> $statements
 * @property string|null $hashed_ip_address
 * @property Carbon $editable_until
 * @property LevelOfEducation|null $level_of_education The level of education according to International Standard Classification of Education (ISCED)
 * @property string|null $going_to_vote
 */
class Response extends Model
{
    use BelongsToElection, HasFactory, HasUuids;

    public $primaryKey = 'uuid';

    public $timestamps = false;

    protected $casts = [
        'created_at' => 'datetime',
        'editable_until' => 'datetime',
        'level_of_education' => LevelOfEducation::class,
    ];

    protected $fillable = [
        'created_at',
        'age',
        'country_id',
        'language_code',
        'gender',
        'hashed_ip_address',
        'election_id',
        'editable_until',
        'level_of_education',
        'going_to_vote',
    ];

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
