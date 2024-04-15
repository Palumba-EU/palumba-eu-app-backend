<?php

namespace App\Models;

use App\Models\Traits\Publishable;
use App\Services\CrowdIn\CrowdIn;
use App\Services\CrowdIn\Translatable;
use App\Services\CrowdIn\TranslatableFile;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

/**
 * A mood image
 *
 * @property int $id
 * @property Carbon $createdAt
 * @property Carbon $updatedAt
 * @property bool $published
 * @property int $party_id
 * @property Party $party
 * @property string $image
 * @property string|null $link
 * @property string|null $link_text
 */
class MoodImage extends Model implements Translatable
{
    use CrowdIn, HasFactory,Publishable;

    protected $fillable = ['party_id', 'image', 'link', 'link_text', 'published'];

    public function party(): BelongsTo
    {
        return $this->belongsTo(Party::class);
    }

    public function getTranslatableAttributes(): array
    {
        return ['link_text'];
    }

    public function getTranslatableFiles(): array
    {
        return [
            new TranslatableFile('image', Storage::disk('public')->path($this->image), sprintf('mood image of %s', $this->party->name), $this->updated_at),
        ];
    }

    public static function getRelationshipsToEagerLoad(): array
    {
        return ['party'];
    }
}
