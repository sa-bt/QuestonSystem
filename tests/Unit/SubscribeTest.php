<?php

namespace Tests\Unit;

use App\Models\Subscribe;
use App\Models\Thread;
use App\Models\User;
use Laravel\Sanctum\Sanctum;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class SubscribeTest extends TestCase
{

    public function test_user_can_subscribe_thread()
    {
        $thread = Thread::factory()->create();
        Sanctum::actingAs($thread->user);

        $response = $this->post(route('subscribe', $thread))->assertSuccessful();
        $response->assertJson([
                                  'message' => 'user subscribed successfully.'
                              ]);
        $response->assertStatus(Response::HTTP_CREATED);
    }

    public function test_user_can_unsubscribe_thread()
    {
        $this->withoutExceptionHandling();
        $subscribe = Subscribe::factory()->create();
        Sanctum::actingAs($subscribe->user);
        $response = $this->postJson(route('unsubscribe', $subscribe->thread))->assertSuccessful();
        $response->assertJson([
                                  'message' => 'user unSubscribed successfully.'
                              ]);
        $response->assertStatus(Response::HTTP_OK);
    }

//    public function notification_will_send_to_subccribers_of_a_thread()
//    {
//
//    }

}
