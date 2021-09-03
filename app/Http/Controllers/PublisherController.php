<?php

namespace App\Http\Controllers;

use App\Services\PublisherService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class PublisherController extends Controller
{
    /**
     * @var PublisherService
     */
    private $publisherService;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(PublisherService $publisherService)
    {
        $this->publisherService = $publisherService;
    }

    public function subscribe(string $topic, Request $request)
    {
        if (!Validator::make($request->all(), ['url' => ['required', 'url'],])) {
            return $this->errorResponse('Please provide a URL');
        }

        $subscribeResponse = $this->publisherService->subscribe($topic, $request->url);

        if ($subscribeResponse["status"]) {
            return $this->jsonResponse($subscribeResponse["data"]);
        }

        return $this->errorResponse($subscribeResponse["msg"], 500);
    }

    public function publish(Request $request, string $topic)
    {
        if (Validator::make($request->all(), [ 'url' => [ 'required', 'json' ], ]) == false) {
            return $this->successResponse('Please enter a valid JSON payload');
        }

        $published = $this->publisherService->publish($topic, $request->toArray());

        if ($published["status"]) {
            return $this->successResponse($published["msg"], $published["data"]);
        }

        return $this->errorResponse($published["msg"], $published["code"]);
    }
}
