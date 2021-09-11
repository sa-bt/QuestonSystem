<?php

namespace Tests\Unit;

use App\Models\Channel;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class ChannelControllerTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_all_channels_list_should_be_accessible()
    {
        $response = $this->get(route('channel.index'));

        $response->assertStatus(Response::HTTP_OK);
    }


    public function test_channel_can_be_created()
    {
        $response = $this->postJson(route('channel.store'), [
            'name' => 'test',
        ]);

        $response->assertStatus(201);
    }


    public function test_channel_before_create_should_be_validate()
    {
        $response = $this->postJson(route('channel.store'), []);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function test_channel_before_update_should_be_validate()
    {
        $channel = Channel::factory()->create();

        $response = $this->putJson(route('channel.update', $channel->id), []);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function test_channel_update()
    {
        $channel = Channel::factory()->create();

        $response = $this->putJson(route('channel.update', $channel->id), [
            'name' => 'Seyed Ahmad Bakhshian'
        ]);

        $channel = Channel::find($channel->id);

        $response->assertStatus(Response::HTTP_OK);

        $this->assertEquals('Seyed Ahmad Bakhshian', $channel->name);
    }

    public function test_delete_channel()
    {
        $channel = Channel::factory()->create();

        $response = $this->deleteJson(route('channel.destroy', $channel->id));

        $response->assertStatus(Response::HTTP_OK);
    }
}
