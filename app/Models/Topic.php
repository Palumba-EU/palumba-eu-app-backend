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
 * @property string extreme1
 * @property string extreme2
 * @property Collection<Statement> $statements
 */
#[ObservedBy([AuditLogObserver::class])]
class Topic extends Model implements Translatable
{
    use CrowdIn, HasFactory, Publishable;

    protected $fillable = ['name', 'color', 'icon', 'published', 'extreme1', 'extreme2'];

    public function statements(): BelongsToMany
    {
        return $this->belongsToMany(Statement::class);
    }

    public function getTranslatableAttributes(): array
    {
        return ['name', 'extreme1', 'extreme2'];
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
