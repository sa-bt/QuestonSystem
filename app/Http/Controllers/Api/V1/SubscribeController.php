<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Subscribe;
use App\Models\Thread;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SubscribeController extends Controller
{

    public function __construct()
    {
        $this->middleware(['user-block']);
    }
    public function subscribe(Thread $thread)
    {
        auth()->user()->subscribes()->create([
            'thread_id'=>$thread->id
                                             ]);
        return response()->json([
                                    "message" => "user subscribed successfully."
                                ], Response::HTTP_CREATED);
    }


    public function unSubscribe(Thread $thread)
    {
        Subscribe::query()->where([
            ['thread_id'=>$thread->id],
            ['user_id'=>auth()->user()->id],
                                  ])
        ->delete();
        return response()->json([
                                    "message" => "user unSubscribed successfully."
                                ], Response::HTTP_OK);
    }
}
