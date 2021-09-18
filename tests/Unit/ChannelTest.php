<?php

namespace Tests\Unit;

use App\Models\Channel;
use App\Models\User;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;
use Laravel\Sanctum\Sanctum;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class ChannelTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_all_channels_list_should_be_accessible()
    {
        $response = $this->get(route('channels.index'));

        $response->assertStatus(Response::HTTP_OK);
    }


    public function test_channel_can_be_created()
    {
        Artisan::call('db:seed', [
            '--class' => 'RoleAndPermissionSeeder',
        ]);

        $user = User::factory()->create();
        $user->givePermissionTo('channel management');
        Sanctum::actingAs($user);
        $response = $this->postJson(route('channels.store'), [
            'name' => 'test',
        ]);

        $response->assertStatus(Response::HTTP_CREATED);
    }


    public function test_channel_before_create_should_be_validate()
    {
        $user = User::factory()->create();
        $user->givePermissionTo('channel management');
        Sanctum::actingAs($user);
        $response = $this->postJson(route('channels.store'), []);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function test_channel_before_update_should_be_validate()
    {
        $channel = Channel::factory()->create();

        $response = $this->putJson(route('channels.update', $channel->id), []);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function test_channel_update()
    {
        $channel = Channel::factory()->create();

        $response = $this->putJson(route('channels.update', $channel->id), [
            'name' => 'Seyed Ahmad Bakhshian'
        ]);

        $channel = Channel::find($channel->id);

        $response->assertStatus(Response::HTTP_OK);

        $this->assertEquals('Seyed Ahmad Bakhshian', $channel->name);
    }

    public function test_delete_channel()
    {
        $channel = Channel::factory()->create();

        $response = $this->deleteJson(route('channels.destroy', $channel->id));

        $response->assertStatus(Response::HTTP_OK);
        $this->assertTrue(Channel::query()->whereId($channel->id)->count()=== 0);
    }
}
