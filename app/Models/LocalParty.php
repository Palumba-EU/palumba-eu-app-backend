<?php

namespace App\Models;

use App\Models\Traits\Publishable;
use App\Observers\AuditLogObserver;
use App\Services\CrowdIn\CrowdIn;
use App\Services\CrowdIn\Translatable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Ramsey\Collection\Collection;

/**
 * A local candidates list
 *
 * @property int $id
 * @property Carbon $createdAt
 * @property Carbon $updatedAt
 * @property bool $published
 * @property string $name
 * @property int $country_id
 * @property Country $country
 * @property int $party_id
 * @property Collection<Party> $parties
 * @property string $logo
 * @property string $link
 * @property string $internal_notes
 * @property string $acronym
 * @property string $description
 * @property string|null $link_text
 */
#[ObservedBy([AuditLogObserver::class])]
class LocalParty extends Model implements Translatable
{
    use CrowdIn, HasFactory, Publishable;

    protected $fillable = [
        'name', 'country_id', 'party_id', 'logo', 'link', 'internal_notes', 'acronym', 'published', 'description', 'link_text'
    ];

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    public function parties(): BelongsToMany
    {
        return $this->belongsToMany(Party::class)->withTimestamps();
    }

    public function getTranslatableAttributes(): array
    {
        return ['name', 'description', 'link_text'];
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
