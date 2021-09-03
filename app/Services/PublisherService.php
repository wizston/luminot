<?php

namespace App\Services;

use App\Models\Topic;
use App\Models\Subscriber;
use Illuminate\Http\Client\Pool;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PublisherService
{
    /**
     * Create new subscription and automatically subscribe to topic
     *
     * @param string $topic
     * @param string $subscriber_url
     *
     *
     * @return Mixed
     */
    public function subscribe(string $topic, string $subscriber_url)
    {
        try {
            Topic::updateOrCreate(['name' => $topic])->addSubscriber($subscriber_url);

            return [
                "status" => true,
                "msg" => null,
                "data" => ['url' => $subscriber_url, 'topic' => $topic]
            ];
        } catch (\Exception $exception) {
            return [
                "status" => false,
                "msg" => "An error occurred while creating/subscribing to topic! Please try again later",
                "data" => null
            ];
        }
    }

    /**
     * Create new subscription and automatically subscribe to topic
     *
     * @param string $topic
     * @param $payload
     *
     *
     * @return Mixed
     */
    public function publish(string $topic, $payload)
    {
        try {
            $topic = Topic::where('name', $topic)->with('subscribers')->first();

            if (! $topic) {
                return [
                    "status" => false,
                    "msg" => "The topic $topic does not exist!",
                    "code" => 404
                ];
            }

            $subscribers = $topic->subscribers()->pluck('url');

            $responses = Http::pool(function (Pool $pool) use ($subscribers, $topic, $payload) {
                $arrayPools = [];
                foreach ($subscribers as $subscriber) {
                    $arrayPools[] = $pool->post($subscriber, [ 'form_params' => [ 'topic' => $topic, 'data' => (object) $payload ] ]);
                }
                return [ $arrayPools ];
            });

            $data = [ "total" => count($responses), "totalSuccess" => 0, "totalFailed" => 0 ];
            foreach ($responses as $response) {
                if (get_class($response) == 'Illuminate\Http\Client\Response') {
                    $data["totalSuccess"] += 1;
                } else {
                    $data["totalFailed"] += 1;
                }
            }

            return [
                "status" => true,
                "msg" => "Broadcasts sent successfully",
                "code" => 200,
                "data" => $data
            ];

        } catch (\Exception $exception) {
            Log::error($exception);

            return [
                "status" => false,
                "msg" => "An error occurred while sending broadcast",
                "code" => 500,
                "data" => null
            ];
        }
    }
}
