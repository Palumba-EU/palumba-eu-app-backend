<?php

namespace App\Models;

use App\Models\Traits\Publishable;
use App\Observers\AuditLogObserver;
use App\Services\CrowdIn\CrowdIn;
use App\Services\CrowdIn\Translatable;
use App\Services\CrowdIn\TranslatableFile;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

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
#[ObservedBy([AuditLogObserver::class])]
class Sponsor extends Model implements Translatable
{
    use CrowdIn, HasFactory, Publishable;

    protected $fillable = ['name', 'logo', 'link', 'banner_image', 'banner_link', 'banner_description', 'category', 'published'];

    public function getTranslatableAttributes(): array
    {
        return ['name', 'banner_link', 'banner_description'];
    }

    public function getTranslatableFiles(): array
    {
        return [
            new TranslatableFile('banner_image', Storage::disk('public')->path($this->banner_image), sprintf('banner of %s', $this->name), $this->updated_at),
        ];
    }

    public static function getRelationshipsToEagerLoad(): array
    {
        return [];
    }
}
