<?php

namespace App\Services;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Kreait\Firebase\Contract\Messaging;

class NotificationSubscriptionService
{
    private int $numSubTopics;

    public function __construct(private Messaging $messaging)
    {
        $this->numSubTopics = intval(config('push_notifications.sub_topics', 10));
    }

    /**
     * @return array{
     *      valid: list<non-empty-string>,
     *      unknown: list<non-empty-string>,
     *      invalid: list<non-empty-string>
     *  }
     */
    public function validateRegistrationTokens(string $device): array
    {
        try {
            return $this->messaging->validateRegistrationTokens($device);
        } catch (\Throwable $exception) {
            Log::error('Error validating device token', [
                'exception' => $exception,
            ]);

            return [
                'invalid' => [],
                'valid' => [],
                'unknown' => [$device],
            ];
        }
    }

    /**
     * @param  Collection<string>  $topics
     */
    public function subscribeToTopics(Collection $topics, string $device): void
    {
        $withSubTopics = $topics->flatMap(fn ($topic) => $this->getSubTopic($topic));
        $this->messaging->subscribeToTopics($withSubTopics->toArray(), $device);
    }

    /**
     * @param  Collection<string>  $topics
     */
    public function unsubscribeFromTopics(Collection $topics, string $device): void
    {
        $withSubTopics = $topics->flatMap(fn ($topic) => $this->getSubTopic($topic, true));
        $this->messaging->unsubscribeFromTopics($withSubTopics->toArray(), $device);
    }

    /**
     * Generates a list of subtopics from 1 to $numSubTopics.
     * This way, we can target only a random percentage of users instead of all.
     * E.g. for 10 subtopics, we can target in 10% increments by sending to TopicName-1, TopicName-2, etc.
     *
     * @return Collection<string>
     */
    private function getSubTopic(string $topic, bool $all = false): Collection
    {
        if ($this->numSubTopics < 2) {
            return collect([$topic]);
        }

        $range = collect(range(1, $this->numSubTopics));
        $subTopics = $range->map(fn ($n) => sprintf('%s-%s', $topic, $n));

        // To unsubscribe the device, we want all subtopics so that the device gets unsubscribed everywhere
        if ($all) {
            return $subTopics->merge([$topic]);
        }

        return collect([$topic, $subTopics->random()]);
    }
}
