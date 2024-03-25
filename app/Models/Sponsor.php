<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property Carbon $createdAt
 * @property Carbon $updatedAt
 * @property string $name
 * @property string $logo
 * @property string $link
 * @property string $banner_image
 * @property string $banner_link
 * @property string $category
 */
class Sponsor extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'logo', 'link', 'banner_image', 'banner_link', 'category'];
}
