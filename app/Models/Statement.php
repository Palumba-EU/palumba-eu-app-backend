<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property Carbon $createdAt
 * @property Carbon $updatedAt
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
class Statement extends Model
{
    use HasFactory;

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
}
