<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Thread;
use App\Repositories\ThreadRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

class ThreadController extends Controller
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
        $threads = resolve(ThreadRepository::class)->all();
        return response()->json($threads, Response::HTTP_OK);
    }

    public function show($id)
    {
        $thread = resolve(ThreadRepository::class)->getThread($id);
        return response()->json($thread, Response::HTTP_OK);
    }

    public function store(Request $request)
    {
        $request->validate([
                               'title'      => 'required',
                               'content'    => 'required',
                               'channel_id' => 'required',
                           ]);

        resolve(ThreadRepository::class)->create($request);
        return response()->json(
            [
                'message' => 'thread created successfully'
            ]
            , Response::HTTP_CREATED);
    }


    public function update(Request $request, Thread $thread)
    {

        $request->has('best_answer_id') ?
            $request->validate([
                                   'best_answer_id' => 'required'
                               ])
            :
            $request->validate([
                                   'title'      => 'required',
                                   'content'    => 'required',
                                   'channel_id' => 'required',
                               ]);
        if (Gate::forUser(auth()->user())->allows('update-thread', $thread))
        {
            resolve(ThreadRepository::class)->edit($request, $thread);
            return response()->json(
                [
                    'message' => 'thread edited successfully'
                ]
                , Response::HTTP_OK);
        }
        return response()->json(
            [
                'message' => 'Access denied'
            ]
            , Response::HTTP_FORBIDDEN);


    }

    public function destroy(Thread $thread)
    {
        if (Gate::forUser(auth()->user())->allows('update-thread', $thread))
        {
            resolve(ThreadRepository::class)->delete($thread);
            return response()->json(
                [
                    'message' => 'thread deleted successfully'
                ]
                , Response::HTTP_OK);
        }
        return response()->json(
            [
                'message' => 'Access denied'
            ]
            , Response::HTTP_FORBIDDEN);

    }

}
