<?php

namespace App\Models;

use App\Services\CrowdIn\CrowdIn;
use App\Services\CrowdIn\Translatable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Collection;

/**
 * @property int $id
 * @property Carbon $createdAt
 * @property Carbon $updatedAt
 * @property string $name
 * @property string $color
 * @property string $icon
 * @property Collection<Statement> $statements
 */
class Topic extends Model implements Translatable
{
    use CrowdIn, HasFactory;

    protected $fillable = ['name', 'color', 'icon'];

    public function statements(): BelongsToMany
    {
        return $this->belongsToMany(Statement::class);
    }

    public function getTranslatableAttributes(): array
    {
        return ['name'];
    }

    public function getTranslatableFiles(): array
    {
        return ['icon'];
    }
}
