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
        Channel::query()->create([
                                     'name' => $request->name,
                                     'slug' => Str::slug($request->name),
                                 ]);
    }

    public function edit(Request $request, $id)
    {

        Channel::query()->find($id)->update([
                                                'name' => $request->name,
                                                'slug' => Str::slug($request->name),
                                            ]);
    }

    public function delete($id)
    {

        Channel::query()->find($id)->delete();
    }
}
