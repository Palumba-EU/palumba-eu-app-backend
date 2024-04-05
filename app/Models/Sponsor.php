<?php

namespace App\Models;

use App\Models\Scopes\PublishedScope;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property Carbon $createdAt
 * @property Carbon $updatedAt
 * @property bool $published
 * @property string $name
 * @property string $logo
 * @property string $link
 * @property string $banner_image
 * @property string $banner_link
 * @property string $banner_description
 * @property string $category
 */
#[ScopedBy([PublishedScope::class])]
class Sponsor extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'logo', 'link', 'banner_image', 'banner_link', 'banner_description', 'category', 'published'];
}
