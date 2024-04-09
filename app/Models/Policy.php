<?php

namespace App\Models;

use App\Models\Traits\Publishable;
use Carbon\Carbon;
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
class Policy extends Model
{
    use HasFactory, Publishable;

    protected $fillable = ['title', 'description', 'party_id', 'published'];

    public function party(): BelongsTo
    {
        return $this->belongsTo(Party::class);
    }
}
