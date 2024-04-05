<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Collection;

/**
 * @property int $id
 * @property Carbon|null $created_at
 * @property int|null $age
 * @property int $country_id
 * @property int $language_id
 * @property string|null $gender
 * @property Country $country
 * @property Collection<Statement> $statements
 */
class Response extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['created_at', 'age', 'country_id', 'language_id', 'gender'];

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    public function statements(): BelongsToMany
    {
        return $this->belongsToMany(Statement::class)->withPivot('answer');
    }
}
