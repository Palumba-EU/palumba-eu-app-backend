<?php

namespace App\Http\Controllers;

use App\Http\Resources\NotificationTopicResource;
use App\Services\NotificationTopic;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;
use Kreait\Firebase\Contract\Messaging;

class NotificationsController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        return NotificationTopicResource::collection(NotificationTopic::list());
    }

    public function updateSubscriptions(Request $request, string $language, string $device, Messaging $messaging): Response
    {
        $data = $this->validatedData($request);

        $validationResult = $messaging->validateRegistrationTokens($device);
        if (count($validationResult['invalid']) > 0) {
            abort(400, 'Invalid device registration token');
        }

        if ($data->has('subscribe')) {
            $messaging->subscribeToTopics($data->get('subscribe'), $device);
        }

        if ($data->has('unsubscribe')) {
            $messaging->unsubscribeFromTopics($data->get('unsubscribe'), $device);
        }

        return response()->noContent();
    }

    private function validatedData(Request $request): Collection
    {
        $allowedTopics = NotificationTopic::list()->pluck('id');

        $data = $request->validate([
            'subscribe' => ['sometimes', 'array'],
            'subscribe.*' => [Rule::in($allowedTopics)],
            'unsubscribe' => ['sometimes', 'array'],
            'unsubscribe.*' => [Rule::in($allowedTopics)],
        ]);

        return collect($data);
    }
}
