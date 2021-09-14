<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{

    public function register(Request $request)
    {
        $request->validate([
                               'name'     => ['required'],
                               'email'    => ['required', 'email'],
                               'password' => ['required'],
                           ]);
        resolve(UserRepository::class)->create($request);

        return response()->json([
                                    "message" => "User created successfully."
                                ], Response::HTTP_CREATED);

    }

    public function login(Request $request)
    {
        $request->validate([
                               'email'    => ['required', 'email'],
                               'password' => ['required'],
                           ]);

        if (Auth::attempt($request->only(['email', 'password'])))
        {
            return response()->json(Auth::user(), Response::HTTP_OK);
        }

        throw ValidationException::withMessages([
                                                    'email' => 'incorrect credentials'
                                                ]);
    }

    public function logout()
    {
        Auth::logout();
        return response()->json([
                                    "message" => "Logout Successfully!!!"
                                ], Response::HTTP_OK);
    }

    public function user()
    {
        return response()->json(Auth::user(), Response::HTTP_OK);
    }


}
