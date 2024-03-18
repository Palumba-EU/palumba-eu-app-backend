<?php

namespace App\Models;

use Carbon\Carbon;
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
 */
class Statement extends Model
{
    use HasFactory;

    protected $fillable = [
        'statement',
        'details',
        'footnote',
        'sort_index',
    ];

}
