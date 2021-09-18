<?php


namespace App\Repositories;


use App\Models\Answer;
use App\Models\Channel;
use App\Models\Subscribe;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SubscribeRepository
{


    public function getNotifiableUserIds($thread)
    {
        return Subscribe::query()
                        ->where('thread_id', '=', $thread)
                        ->pluck('user_id')
                        ->all();
    }
}
