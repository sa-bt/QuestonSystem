<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Answer;
use App\Models\Subscribe;
use App\Models\Thread;
use App\Models\User;
use App\Notifications\AnswerNotification;
use App\Repositories\AnswerRepository;
use App\Repositories\SubscribeRepository;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Notification;
use Symfony\Component\HttpFoundation\Response;

class AnswerController extends Controller
{

    public function __construct()
    {
        $this->middleware(['user-block'])->except([
                                                      'show',
                                                      'index'
                                                  ]);
    }

    public function index()
    {
        $answers = resolve(AnswerRepository::class)->all();
        return response()->json($answers
            , Response::HTTP_OK);
    }

    public function store(Request $request)
    {
        $request->validate([
                               'content'   => ['required'],
                               'thread_id' => ['required'],
                           ]);

        resolve(AnswerRepository::class)->create($request);

        $thread = $request->input('thread_id');

        //Fetch users id which subscribed to a thread id
        $notifiableUsersId = resolve(SubscribeRepository::class)->getNotifiableUsersId($thread);
        //Get user instance from id
        $notifiableUsers = resolve(UserRepository::class)->find($notifiableUsersId);
        //Send answer notification to subscribed users
        $thread = Thread::query()->find($thread);
        Notification::send($notifiableUsers, new AnswerNotification($thread));

        //Increase user score
        if ($thread->user_id != auth()->id())
        {
            auth()->user()->increment('score', 10);
        }
        return response()->json([
                                    "message" => "answer created successfully."
                                ], Response::HTTP_CREATED);
    }

    public function show($id)
    {
        //
    }

    public function update(Request $request, Answer $answer)
    {
        $request->validate([
                               'content' => ['required']
                           ]);

        if (Gate::forUser(auth()->user())->allows('update-answer', $answer))
        {
            resolve(AnswerRepository::class)->edit($request, $answer);
            return response()->json([
                                        "message" => "answer updated successfully."
                                    ], Response::HTTP_OK);
        }

        return response()->json([
                                    "message" => "access denied."
                                ], Response::HTTP_FORBIDDEN);
    }

    public function destroy(Answer $answer)
    {
        if (Gate::forUser(auth()->user())->allows('update-answer', $answer))
        {
            resolve(AnswerRepository::class)->delete($answer);
            return response()->json([
                                        "message" => "answer deleted successfully."
                                    ], Response::HTTP_OK);
        }

        return response()->json([
                                    "message" => "access denied."
                                ], Response::HTTP_FORBIDDEN);
    }

}
