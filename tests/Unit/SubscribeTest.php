<?php

namespace Tests\Unit;

use App\Models\Subscribe;
use App\Models\Thread;
use App\Models\User;
use App\Notifications\AnswerNotification;
use Illuminate\Support\Facades\Notification;
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

    public function test_notification_will_send_to_subscribers_of_a_thread()
    {
        $thread = Thread::factory()->create();
        Sanctum::actingAs($thread->user);
        Notification::fake();

        $threadResponse = $this->post(route('subscribe', $thread))->assertSuccessful();
        $threadResponse->assertJson([
                                        'message' => 'user subscribed successfully.'
                                    ]);

        $answerResponse = $this->postJson(route('answers.store'), [
            'content'   => 'sample test',
            'thread_id' => $thread->id
        ]);
        $answerResponse->assertSuccessful();
        $answerResponse->assertJson([
                                        'message' => 'answer created successfully.'
                                    ]);

        Notification::assertSentTo(auth()->user(), AnswerNotification::class);
    }

    public function test_user_score_will_increase_when_submit_new_answer()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $thread = Thread::factory()->create();
        $response = $this->postJson(route('answers.store'), [
            'thread_id' => $thread->id,
            'content'   => 'test content',
        ]);
        $response->assertSuccessful();
        $user->refresh();
        $this->assertEquals(10, $user->score);
    }
    public function test_user_creator_thread_will_not_increase_score_when_submit_new_answer()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $thread = Thread::factory()->create();
        $response = $this->postJson(route('answers.store'), [
            'thread_id' => $thread->id,
            'content'   => 'test content',
        ]);
        $thread->user->refresh();
        $this->assertEquals(0, $thread->user->score);
    }

}
