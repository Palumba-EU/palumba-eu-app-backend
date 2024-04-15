<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Traits\HasRoles;

/**
 * @property Carbon $createdAt
 * @property Carbon $updatedAt
 * @property Carbon $valid_until
 * @property string $email
 * @property string|null $code
 */
class UserInvitation extends Model
{
    use HasFactory, HasRoles;

    protected $casts = [
        'valid_until' => 'datetime:Y-m-d',
    ];

    protected $fillable = [
        'email',
        'code',
        'valid_until',
    ];
}
