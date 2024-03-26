<?php

namespace App\Models;

use App\Services\CrowdIn\CrowdIn;
use App\Services\CrowdIn\Translatable;
use App\Services\CrowdIn\TranslatableFile;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

/**
 * @property int $id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property string $name
 * @property string $logo
 * @property string $link
 * @property string $banner_image
 * @property string $banner_link
 * @property string $category
 */
class Sponsor extends Model implements Translatable
{
    use CrowdIn, HasFactory;

    protected $fillable = ['name', 'logo', 'link', 'banner_image', 'banner_link', 'category'];

    public function getTranslatableAttributes(): array
    {
        return ['name'];
    }

    public function getTranslatableFiles(): array
    {
        return [
            new TranslatableFile('banner_image', Storage::disk('public')->path($this->banner_image), sprintf('banner of %s', $this->name), $this->updated_at),
        ];
    }
}
