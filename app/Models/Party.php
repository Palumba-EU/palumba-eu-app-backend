<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property Carbon $createdAt
 * @property Carbon $updatedAt
 * @property string $name
 * @property int $country_id
 * @property Country $country
 * @property string $color
 */
class Party extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'country_id',
        'color',
    ];

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

}
