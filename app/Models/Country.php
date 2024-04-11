<?php

namespace App\Models;

use App\Models\Traits\Publishable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property Carbon $createdAt
 * @property Carbon $updatedAt
 * @property bool $published
 * @property string $name
 * @property string $code
 * @property string $flag
 */
class Country extends Model
{
    use HasFactory, Publishable;

    protected $fillable = [
        'name',
        'code',
        'flag',
        'published',
    ];
}
