<?php

namespace Tests\Unit;

use App\Services\NotificationSubscriptionService;
use Illuminate\Support\Facades\Config;
use Kreait\Firebase\Contract\Messaging;
use Mockery;
use Mockery\MockInterface;
use Tests\TestCase;

class NotificationSubscriptionServiceTest extends TestCase
{
    public function test_device_is_subscribed_to_topic_and_one_random_sub_topic(): void
    {
        Config::set('push_notifications.sub_topics', 5);

        $expectedTopics = collect(['test-1', 'test-2', 'test-3', 'test-4', 'test-5']);

        $messaging = Mockery::mock(Messaging::class, function (MockInterface $mock) use ($expectedTopics) {
            $mock->shouldReceive('subscribeToTopics')->once()->withArgs(function (array $topics, $device) use ($expectedTopics) {
                $topics = collect($topics);

                return $topics->count() == 2 && $topics->contains('test') && $topics->some(fn ($topic) => $expectedTopics->contains($topic)) && $device == 'device';
            });
        });

        $service = new NotificationSubscriptionService($messaging);

        $service->subscribeToTopics(collect(['test']), 'device');
    }

    public function test_device_is_unsubscribed_from_topic_and_all_sub_topics()
    {
        Config::set('push_notifications.sub_topics', 5);

        $expectedTopics = collect(['test-1', 'test-2', 'test-3', 'test-4', 'test-5']);

        $messaging = Mockery::mock(Messaging::class, function (MockInterface $mock) use ($expectedTopics) {
            $mock->shouldReceive('unsubscribeFromTopics')->once()->withArgs(function (array $topics, $device) use ($expectedTopics) {
                $topics = collect($topics);

                return $topics->count() == 6 && $topics->contains('test') && $expectedTopics->every(fn ($topic) => $topics->contains($topic)) && $device == 'device';
            });
        });

        $service = new NotificationSubscriptionService($messaging);

        $service->unsubscribeFromTopics(collect(['test']), 'device');
    }
}
