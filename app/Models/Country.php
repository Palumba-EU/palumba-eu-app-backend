<?php

namespace App\Models;

use App\Models\Traits\Publishable;
use App\Observers\AuditLogObserver;
use App\Services\CrowdIn\CrowdIn;
use App\Services\CrowdIn\Translatable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

/**
 * Represents a country or a region within a country.
 *
 * @property int $id
 * @property Carbon $createdAt
 * @property Carbon $updatedAt
 * @property bool $published
 * @property string $name
 * @property string $code
 * @property string $flag
 * @property Collection<LocalParty> $local_parties
 * @property Collection<Response> $responses
 * @property int|null $parent_id Null if this is a country, otherwise the id of the country this is a region in
 * @property Country|null $parent
 */
#[ObservedBy([AuditLogObserver::class])]
class Country extends Model implements Translatable
{
    use CrowdIn, HasFactory, Publishable;

    protected $fillable = [
        'name',
        'code',
        'flag',
        'published',
        'parent_id',
    ];

    public function local_parties(): HasMany
    {
        return $this->hasMany(LocalParty::class);
    }

    public function responses(): HasMany
    {
        return $this->hasMany(Response::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Country::class, 'parent_id');
    }

    public function scopeCountry(Builder $query)
    {
        $query->whereNull('parent_id');
    }

    public function scopeRegion(Builder $query)
    {
        $query->whereNotNull('parent_id');
    }

    public function scopeParent(Builder $query, Country|int|null $parent)
    {
        $query->where('parent_id', is_int($parent) ? $parent : $parent?->id);
    }

    public function getTranslatableAttributes(): array
    {
        return ['name'];
    }

    public function getTranslatableFiles(): array
    {
        return [];
    }

    public static function getRelationshipsToEagerLoad(): array
    {
        return [];
    }
}
