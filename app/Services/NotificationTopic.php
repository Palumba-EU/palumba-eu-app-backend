<?php

namespace App\Services;

use App\Models\Election;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class NotificationTopic
{
    public function __construct(
        public readonly string $id,
        public readonly string $name,
        public readonly int $relatedId
    ) {}

    /**
     * @return Collection<int, NotificationTopic>
     */
    public static function list(): Collection
    {
        return Election::query()->get()->map(function (Election $election) {
            return new NotificationTopic(
                $election->notification_topic,
                $election->getTranslationForAttribute('name'),
                $election->id
            );
        });
    }
}
