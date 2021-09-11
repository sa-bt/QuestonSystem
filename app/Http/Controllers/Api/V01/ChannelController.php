<?php

namespace App\Http\Controllers\Api\V01;

use App\Http\Controllers\Controller;
use App\Models\Channel;
use App\Repositories\ChannelRepository;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ChannelController extends Controller
{

    public function index()
    {
        $channels = resolve(ChannelRepository::class)->all();
        return response()->json($channels, Response::HTTP_OK);
    }


    public function store(Request $request)
    {
        $request->validate([
                               'name' => ['required']
                           ]);

        resolve(ChannelRepository::class)->create($request);

        return response()->json([
                                    "message" => "Channel created successfully."
                                ], Response::HTTP_CREATED);

    }

    public function update(Request $request, $id)
    {

        $request->validate([
                               'name' => ['required']
                           ]);

        resolve(ChannelRepository::class)->edit($request, $id);

        return response()->json([
                                    "message" => "Channel edited successfully."
                                ], Response::HTTP_OK);

    }

    public function destroy($id)
    {

        resolve(ChannelRepository::class)->delete($id);

        return response()->json([
                                    "message" => "Channel deleted successfully!!!"
                                ], Response::HTTP_OK);

    }
}
