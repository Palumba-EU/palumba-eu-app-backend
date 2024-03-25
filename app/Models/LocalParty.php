<?php

namespace App\Models;

use App\Services\CrowdIn\CrowdIn;
use App\Services\CrowdIn\Translatable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * A local party
 *
 * @property int $id
 * @property Carbon $createdAt
 * @property Carbon $updatedAt
 * @property string $name
 * @property int $country_id
 * @property Country $country
 * @property int $party_id
 * @property Party $party
 * @property string $logo
 * @property string $link
 */
class LocalParty extends Model implements Translatable
{
    use CrowdIn, HasFactory;

    protected $fillable = [
        'name', 'country_id', 'party_id', 'logo', 'link',
    ];

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    public function party(): BelongsTo
    {
        return $this->belongsTo(Party::class);
    }

    public function getTranslatableAttributes(): array
    {
        return ['name'];
    }

    public function getTranslatableFiles(): array
    {
        return ['logo'];
    }

}
