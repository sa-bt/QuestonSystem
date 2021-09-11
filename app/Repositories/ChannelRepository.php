<?php


namespace App\Repositories;


use App\Models\Channel;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ChannelRepository
{


    public function all()
    {
        return Channel::all();
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

    public function delete( $id)
    {

        Channel::query()->find($id)->delete();
    }
}
