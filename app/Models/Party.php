<?php

namespace App\Models;

use App\Models\Scopes\PublishedScope;
use App\Services\CrowdIn\CrowdIn;
use App\Services\CrowdIn\Translatable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

/**
 * An EU level party/group
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
 * @property int $p1
 * @property int $p2
 * @property int $p3
 * @property int $p4
 * @property int $p5
 * @property array<int> $position
 * @property Collection<Policy> $policies
 * @property Collection<LocalParty> $local_parties
 * @property Collection<MoodImage> $mood_images
 */
#[ScopedBy([PublishedScope::class])]
class Party extends Model implements Translatable
{
    use CrowdIn, HasFactory;

    protected $fillable = [
        'name',
        'color',
        'logo',
        'link',
        'acronym',
        'p1',
        'p2',
        'p3',
        'p4',
        'p5',
        'published',
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

    public function position(): Attribute
    {
        return Attribute::make(
            get: fn () => [$this->p1, $this->p2, $this->p3, $this->p4, $this->p5],
            set: function (array $value) {
                $this->p1 = $value[0];
                $this->p2 = $value[1];
                $this->p3 = $value[2];
                $this->p4 = $value[3];
                $this->p5 = $value[4];
            }
        );
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
