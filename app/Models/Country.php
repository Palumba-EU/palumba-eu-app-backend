<?php

namespace App\Models;

use App\Models\Traits\Publishable;
use App\Observers\AuditLogObserver;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

/**
 * @property int $id
 * @property Carbon $createdAt
 * @property Carbon $updatedAt
 * @property bool $published
 * @property string $name
 * @property string $code
 * @property string $flag
 * @property Collection<Party> $parties
 * @property Collection<LocalParty> $local_parties
 * @property Collection<Response> $responses
 */
#[ObservedBy([AuditLogObserver::class])]
class Country extends Model
{
    use HasFactory, Publishable;

    protected $fillable = [
        'name',
        'code',
        'flag',
        'published',
    ];

    public function parties(): HasMany
    {
        return $this->hasMany(Party::class);
    }

    public function local_parties(): HasMany
    {
        return $this->hasMany(LocalParty::class);
    }

    public function responses(): HasMany
    {
        return $this->hasMany(Response::class);
    }
}
