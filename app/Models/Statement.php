<?php

namespace App\Models;

use App\Models\Traits\BelongsToElection;
use App\Models\Traits\Publishable;
use App\Observers\AuditLogObserver;
use App\Services\CrowdIn\CrowdIn;
use App\Services\CrowdIn\Translatable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;

/**
 * @property int $id
 * @property Carbon $createdAt
 * @property Carbon $updatedAt
 * @property bool $published
 * @property string $statement
 * @property string $details
 * @property string $footnote
 * @property int $sort_index
 * @property string $emojis
 * @property Collection<Response> $responses
 * @property Collection<Topic> $weights
 * @property int $election_id
 * @property Election $election
 * @property bool $is_tutorial
 */
#[ObservedBy([AuditLogObserver::class])]
class Statement extends Model implements Translatable
{
    use BelongsToElection, CrowdIn, HasFactory, Publishable, SoftDeletes;

    protected $fillable = [
        'statement',
        'details',
        'footnote',
        'sort_index',
        'emojis',
        'published',
        'election_id',
        'is_tutorial',
    ];

    protected $casts = [
        'is_tutorial' => 'boolean',
    ];

    public function parties(): BelongsToMany
    {
        return $this->belongsToMany(Party::class)->withTimestamps()->withPivot(['answer']);
    }

    public function responses(): BelongsToMany
    {
        return $this->belongsToMany(Response::class)->withPivot(['answer']);
    }

    public function weights(): BelongsToMany
    {
        return $this->belongsToMany(Topic::class, 'statement_topic_weights')->withTimestamps()->withPivot(['weight']);
    }

    public function scopeWithoutTutorial(Builder $query): void
    {
        $query->where('is_tutorial', '=', false);
    }

    public function getTranslatableAttributes(): array
    {
        return ['statement', 'details', 'footnote'];
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
