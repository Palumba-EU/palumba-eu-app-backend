<?php

namespace App\Models;

use App\Models\Traits\BelongsToElection;
use App\Models\Traits\Publishable;
use App\Observers\AuditLogObserver;
use App\Services\CrowdIn\CrowdIn;
use App\Services\CrowdIn\Translatable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

/**
 * An electable party or group.
 * For EU level elections, these are EU parties/groups
 * For country level elections, these are local parties/groups
 *
 * @property int $id
 * @property Carbon $createdAt
 * @property Carbon $updatedAt
 * @property bool $published
 * @property string $name
 * @property string $color
 * @property string $logo
 * @property string $link
 * @property string $acronym
 * @property Collection<Policy> $policies
 * @property Collection<LocalParty> $local_parties
 * @property Collection<MoodImage> $mood_images
 * @property Collection<Topic> $positions
 * @property int $election_id
 * @property Election $election
 * @property bool $in_parliament
 * @property Collection<Country> $unavailable_in_countries
 */
#[ObservedBy([AuditLogObserver::class])]
class Party extends Model implements Translatable
{
    use BelongsToElection, CrowdIn, HasFactory, Publishable;

    protected $fillable = [
        'name',
        'color',
        'logo',
        'link',
        'acronym',
        'published',
        'election_id',
        'in_parliament',
    ];

    protected $casts = [
        'in_parliament' => 'boolean',
    ];

    public function policies(): HasMany
    {
        return $this->hasMany(Policy::class);
    }

    public function local_parties(): BelongsToMany
    {
        return $this->belongsToMany(LocalParty::class)->withTimestamps();
    }

    public function mood_images(): HasMany
    {
        return $this->hasMany(MoodImage::class);
    }

    public function statements(): BelongsToMany
    {
        return $this->belongsToMany(Statement::class)->withTimestamps()->withPivot(['answer']);
    }

    public function positions(): BelongsToMany
    {
        return $this->belongsToMany(Topic::class, 'party_topic_positions')->withTimestamps()->withPivot(['position']);
    }

    public function unavailable_in_countries(): BelongsToMany
    {
        return $this->belongsToMany(Country::class);
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
