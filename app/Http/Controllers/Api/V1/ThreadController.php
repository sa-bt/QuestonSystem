<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Thread;
use App\Repositories\ThreadRepository;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ThreadController extends Controller
{
    public function index()
    {
        $threads= resolve(ThreadRepository::class)->all();
        return response()->json($threads,Response::HTTP_OK);
    }

    public function show($id)
    {
        $thread= resolve(ThreadRepository::class)->getThread($id);
        return response()->json($thread,Response::HTTP_OK);
    }
}
