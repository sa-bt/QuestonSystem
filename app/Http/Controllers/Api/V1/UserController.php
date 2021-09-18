<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    public function notifications()
    {
        return response()->json(auth()->user()->unreadNotifications(), Response::HTTP_OK);
    }
}
