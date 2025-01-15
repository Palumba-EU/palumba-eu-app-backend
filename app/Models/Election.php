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
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Collection;

/**
 * @property int $id
 * @property Carbon $createdAt
 * @property Carbon $updatedAt
 * @property bool $published
 * @property Carbon $date
 * @property string $name
 * @property int|null $country_id
 * @property Country|null $country
 * @property Collection<Language> $languages
 */
#[ObservedBy([AuditLogObserver::class])]
class Election extends Model implements Translatable
{
    use CrowdIn, HasFactory, Publishable;

    protected $casts = [
        'date' => 'datetime',
    ];

    protected $fillable = [
        'published',
        'name',
        'date',
        'country_id',
    ];

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    public function languages(): BelongsToMany
    {
        return $this->belongsToMany(Language::class);
    }

    public function availableLanguages(): Builder
    {
        if ($this->languages()->count() > 0) {
            return $this->languages()->getQuery();
        }

        return Language::query();
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

    /**
     * Returns the very first election that was created.
     * Used for keeping endpoints backwards compatible.
     */
    public static function default(): Election
    {
        return Election::query()->orderBy('id')->firstOrFail();
    }
}
