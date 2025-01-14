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
use Illuminate\Support\Collection;

/**
 * @property int $id
 * @property Carbon $createdAt
 * @property Carbon $updatedAt
 * @property bool $published
 * @property string $name
 * @property string $color
 * @property string $icon
 * @property string $extreme1
 * @property string $extreme1_details
 * @property string $extreme1_emojis
 * @property string $extreme2
 * @property string $extreme2_details
 * @property string $extreme2_emojis
 * @property Collection<Statement> $statements
 * @property int $election_id
 * @property Election $election
 */
#[ObservedBy([AuditLogObserver::class])]
class Topic extends Model implements Translatable
{
    use BelongsToElection, CrowdIn, HasFactory, Publishable;

    protected $fillable = ['name', 'color', 'icon', 'published', 'extreme1', 'extreme2', 'extreme1_details', 'extreme2_details', 'extreme1_emojis', 'extreme2_emojis'];

    public function statements(): BelongsToMany
    {
        return $this->belongsToMany(Statement::class);
    }

    public function party_positions(): BelongsToMany
    {
        return $this->belongsToMany(Party::class, 'party_topic_positions')->withTimestamps()->withPivot(['position']);
    }

    public function statement_weights(): BelongsToMany
    {
        return $this->belongsToMany(Statement::class, 'statement_topic_weights')->withTimestamps()->withPivot(['weight']);
    }

    public function getTranslatableAttributes(): array
    {
        return ['name', 'extreme1', 'extreme2', 'extreme1_details', 'extreme2_details'];
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
