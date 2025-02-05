<?php

namespace App\Models;

use App\Models\Traits\Publishable;
use App\Observers\AuditLogObserver;
use App\Services\CrowdIn\CrowdIn;
use App\Services\CrowdIn\Translatable;
use App\Services\CrowdIn\TranslatableFile;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;

/**
 * @property int $id
 * @property Carbon $createdAt
 * @property Carbon $updatedAt
 * @property bool $published
 * @property Carbon $date
 * @property string $name
 * @property int|null $country_id
 * @property Country|null $country
 * @property Collection<Language> $languages
 * @property string $egg_title
 * @property string $egg_description
 * @property string $egg_image
 * @property string $egg_yes_btn_text
 * @property string $egg_yes_btn_link
 * @property string $egg_no_btn_text
 */
#[ObservedBy([AuditLogObserver::class])]
class Election extends Model implements Translatable
{
    use CrowdIn, HasFactory, Publishable;

    protected $casts = [
        'date' => 'datetime',
    ];

    protected $fillable = [
        'published',
        'name',
        'date',
        'country_id',
        'egg_title',
        'egg_description',
        'egg_image',
        'egg_yes_btn_text',
        'egg_yes_btn_link',
        'egg_no_btn_text',
    ];

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    public function languages(): BelongsToMany
    {
        return $this->belongsToMany(Language::class);
    }

    public function availableLanguages(): Builder
    {
        if ($this->languages()->count() > 0) {
            return $this->languages()->getQuery();
        }

        return Language::query();
    }

    public function getTranslatableAttributes(): array
    {
        return ['name', 'egg_title', 'egg_description', 'egg_yes_btn_text', 'egg_yes_btn_link', 'egg_no_btn_text'];
    }

    public function getTranslatableFiles(): array
    {
        return [
            new TranslatableFile('egg_image', Storage::disk('public')->path($this->egg_image), sprintf('Egg Screen image of %s', $this->name), $this->updated_at),
        ];
    }

    public static function getRelationshipsToEagerLoad(): array
    {
        return [];
    }

    /**
     * Returns the very first election that was created.
     * Used for keeping endpoints backwards compatible.
     */
    public static function default(): Election
    {
        return Election::query()->orderBy('id')->firstOrFail();
    }
}
