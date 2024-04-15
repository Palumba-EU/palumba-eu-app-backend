<?php

namespace App\Models;

use App\Models\Traits\Publishable;
use App\Observers\AuditLogObserver;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
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
#[ObservedBy([AuditLogObserver::class])]
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
