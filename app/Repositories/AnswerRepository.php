<?php


namespace App\Repositories;


use App\Models\Answer;
use App\Models\Channel;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AnswerRepository
{


    public function all()
    {
        return Answer::query()->latest()->get();
    }

    public function create(Request $request)
    {
        Answer::query()->create([
                                    'content'   => $request->input('content'),
                                    'thread_id' => $request->input('thread_id'),
                                    'user_id'   => $request->user()->id,
                                ]);
    }

    public function edit(Request $request, Answer $answer)
    {
        $answer->update([
                            'content' => $request->input('content'),
                        ]);
    }

    public function delete(Answer $answer)
    {

        $answer->delete();
    }
}
