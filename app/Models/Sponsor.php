<?php

namespace App\Models;

use App\Models\Traits\Publishable;
use App\Observers\AuditLogObserver;
use App\Services\CrowdIn\CrowdIn;
use App\Services\CrowdIn\Translatable;
use App\Services\CrowdIn\TranslatableFile;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;

/**
 * @property int $id
 * @property Carbon $createdAt
 * @property Carbon $updatedAt
 * @property bool $published
 * @property string $name
 * @property string $logo
 * @property string $link
 * @property string $banner_image
 * @property string $banner_link
 * @property string $banner_description
 * @property string $category
 * @property Collection<Election> $elections
 */
#[ObservedBy([AuditLogObserver::class])]
class Sponsor extends Model implements Translatable
{
    use CrowdIn, HasFactory, Publishable;

    protected $fillable = ['name', 'logo', 'link', 'banner_image', 'banner_link', 'banner_description', 'category', 'published'];

    public function elections(): BelongsToMany
    {
        return $this->belongsToMany(Election::class);
    }

    /**
     * The relevant sponsors for an election are all that are assigned to that particular election
     * and all global sponsors (because they are "sponsoring globally")
     */
    public function scopeRelevantForElection(Builder $query, Election $election)
    {
        $query->where(fn ($q) => $q->election($election))->orWhere(fn ($q) => $q->global());
    }

    public function scopeElection(Builder $query, Election $election)
    {
        $query->whereHas('elections', function (Builder $query) use ($election) {
            $query->where('id', '=', $election->id);
        });
    }

    /**
     * A sponsor assigned to no election is considered global
     */
    public function scopeGlobal(Builder $query)
    {
        $query->doesntHave('elections');
    }

    public function getTranslatableAttributes(): array
    {
        return ['name', 'banner_link', 'banner_description'];
    }

    public function getTranslatableFiles(): array
    {
        return [
            new TranslatableFile('banner_image', Storage::disk('public')->path($this->banner_image), sprintf('banner of %s', $this->name), $this->updated_at),
        ];
    }

    public static function getRelationshipsToEagerLoad(): array
    {
        return [];
    }
}
