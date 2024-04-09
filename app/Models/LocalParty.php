<?php

namespace App\Models;

use App\Models\Traits\Publishable;
use Carbon\Carbon;
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
 */
class LocalParty extends Model
{
    use HasFactory, Publishable;

    protected $fillable = [
        'name', 'country_id', 'party_id', 'logo', 'link', 'internal_notes', 'acronym', 'published',
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
