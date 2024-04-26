<?php

namespace App\Models;

use App\Models\Traits\Publishable;
use App\Observers\AuditLogObserver;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Languages currently get loaded directly from crowdin.
 * The Language model is purely for controlling which of the target languages are exposed via api
 *
 * @property int $id
 * @property Carbon $createdAt
 * @property Carbon $updatedAt
 * @property bool $published
 * @property string $name
 * @property string $code
 */
#[ObservedBy([AuditLogObserver::class])]
class Language extends Model
{
    use HasFactory, Publishable;

    protected $fillable = ['published', 'name', 'code'];
}
