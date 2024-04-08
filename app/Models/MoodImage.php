<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * A mood image
 *
 * @property int $id
 * @property Carbon $createdAt
 * @property Carbon $updatedAt
 * @property int $party_id
 * @property Party $party
 * @property string $image
 * @property string|null $link
 * @property string|null $link_text
 */
class MoodImage extends Model
{
    use HasFactory;

    protected $fillable = ['party_id', 'image', 'link', 'link_text'];

    public function party(): BelongsTo
    {
        return $this->belongsTo(Party::class);
    }
}