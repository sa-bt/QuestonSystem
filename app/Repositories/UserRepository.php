<?php


namespace App\Repositories;


use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserRepository
{


    public function create(Request $request)
    {
        $user = User::query()->create([
                                          'name'     => $request->name,
                                          'email'    => $request->email,
                                          'password' => Hash::make($request->password),
                                      ]);
        in_array($user->email, config('permission.default_super_admin_email')) ?
            $user->assignRole('Super Admin') :
            $user->assignRole('User');

    }

    public function find($id)
    {
        return User::query()->find($id);
    }

    public function leaderBoards()
    {
        return User::query()->orderByDesc('score')->paginate(20);
    }

    public function isBlock()
    {
        return (bool) auth()->user()->is_block;
    }
}
