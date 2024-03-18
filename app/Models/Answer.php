<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * @property int $id
 * @property Carbon $createdAt
 * @property Carbon $updatedAt
 * @property int $statement_id
 * @property Statement $statement
 * @property int $answerable_id
 * @property string $answerable_type
 * @property Statement $answerable
 * @property int $answer
 */
class Answer extends Model
{
    use HasFactory;

    protected $fillable = [
        'answer',
    ];

    public function statement(): BelongsTo
    {
        return $this->belongsTo(Statement::class);
    }

    public function answerable(): MorphTo
    {
        return $this->morphTo();
    }
}
