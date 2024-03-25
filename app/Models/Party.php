<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Collection;

/**
 * @property int $id
 * @property Carbon $createdAt
 * @property Carbon $updatedAt
 * @property string $name
 * @property int $country_id
 * @property Country $country
 * @property string $color
 * @property int $p1
 * @property int $p2
 * @property int $p3
 * @property int $p4
 * @property int $p5
 * @property array<int> $position
 */
class Party extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'country_id',
        'color',
        'p1',
        'p2',
        'p3',
        'p4',
        'p5',
    ];

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
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
}
