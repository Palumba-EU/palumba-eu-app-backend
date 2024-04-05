<?php

namespace App\Models;

use App\Models\Scopes\PublishedScope;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Ramsey\Collection\Collection;

/**
 * A local party
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
 * @property string $acronym
 */
#[ScopedBy([PublishedScope::class])]
class LocalParty extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'country_id', 'party_id', 'logo', 'link', 'acronym', 'published',
    ];

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    public function parties(): BelongsToMany
    {
        return $this->belongsToMany(Party::class)->withTimestamps();
    }
}
