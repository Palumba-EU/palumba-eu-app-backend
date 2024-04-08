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

/**
 * @property int $id
 * @property Carbon $createdAt
 * @property Carbon $updatedAt
 * @property bool $published
 * @property string $statement
 * @property string $details
 * @property string $footnote
 * @property int $sort_index
 * @property int $w1
 * @property int $w2
 * @property int $w3
 * @property int $w4
 * @property int $w5
 * @property array<int> $vector
 * @property string $emojis
 */
#[ScopedBy([PublishedScope::class])]
class Statement extends Model implements Translatable
{
    use CrowdIn, HasFactory;

    protected $fillable = [
        'statement',
        'details',
        'footnote',
        'sort_index',
        'w1',
        'w2',
        'w3',
        'w4',
        'w5',
        'emojis',
        'published',
    ];

    public function vector(): Attribute
    {
        return Attribute::make(
            get: fn () => [$this->w1, $this->w2, $this->w3, $this->w4, $this->w5],
            set: function (array $value) {
                $this->w1 = $value[0];
                $this->w2 = $value[1];
                $this->w3 = $value[2];
                $this->w4 = $value[3];
                $this->w5 = $value[4];
            }
        );
    }

    public function getTranslatableAttributes(): array
    {
        return ['statement', 'details', 'footnote'];
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
