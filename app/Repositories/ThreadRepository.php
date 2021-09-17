<?php


namespace App\Repositories;


use App\Models\Channel;
use App\Models\Thread;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ThreadRepository
{


    public function all()
    {
        return Thread::query()->whereflag(1)->latest()->get();
    }


    public function getThread($id)
    {
        return Thread::query()->find($id);
    }


    public function create(Request $request)
    {
        Thread::query()->create([
                                    'title'      => $request->input('title'),
                                    'slug'       => Str::slug($request->input('title')),
                                    'content'    => $request->input('content'),
                                    'channel_id' => $request->input('channel_id'),
                                    'user_id'    => $request->user()->id,
                                ]);
    }

    public function edit(Request $request, Thread $thread)
    {
        $request->has('best_answer_id') ?
            $thread->update([
                                'answer_id' => $request->input('best_answer_id')
                            ]) :
            $thread->update([
                                'title'      => $request->input('title'),
                                'slug'       => Str::slug($request->input('title')),
                                'content'    => $request->input('content'),
                                'channel_id' => $request->input('channel_id'),
                                'user_id'    => $request->user()->id,
                            ]);
    }

    public function delete(Thread $thread)
    {
        $thread->delete();
    }
}
