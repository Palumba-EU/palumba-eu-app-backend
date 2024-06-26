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

/**
 * @property int $id
 * @property Carbon $createdAt
 * @property Carbon $updatedAt
 * @property bool $published
 * @property string $title
 * @property string $description
 * @property int $party_id
 * @property Party $party
 */
#[ObservedBy([AuditLogObserver::class])]
class Policy extends Model implements Translatable
{
    use CrowdIn, HasFactory, Publishable;

    protected $fillable = ['title', 'description', 'party_id', 'published'];

    public function party(): BelongsTo
    {
        return $this->belongsTo(Party::class);
    }

    public function getTranslatableAttributes(): array
    {
        return ['title', 'description'];
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
