<?php

namespace Tests\Unit;

use App\Models\Channel;
use App\Models\Thread;
use App\Models\User;
use Illuminate\Support\Str;
use Laravel\Sanctum\Sanctum;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class ThreadTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_all_threads_list_should_be_accessible()
    {
        $response = $this->get(route('threads.index'));

        $response->assertStatus(Response::HTTP_OK);
    }

    public function test_thread_should_be_accessible_by_id()
    {
        $thread   = Thread::factory()->create();
        $response = $this->get(route('threads.show', $thread->id));

        $response->assertStatus(Response::HTTP_OK);
    }


    public function test_create_thread_should_be_validated()
    {
        $response = $this->postJson(route('threads.store', []));

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }


    public function test_create_thread()
    {
//        $this->withoutExceptionHandling();

        Sanctum::actingAs(User::factory()->create());
        $response = $this->postJson(route('threads.store', [
            'title'      => 'title',
            'slug'       => Str::slug('title'),
            'content'    => 'content',
            'channel_id' => Channel::factory()->create()->id,
        ]));

        $response->assertStatus(Response::HTTP_CREATED);
    }

    public function test_update_thread_should_be_validated()
    {
        $thread   = Thread::factory()->create();
        $response = $this->putJson(route('threads.update', $thread->id), []);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }


    public function test_update_thread()
    {
//                $this->withoutExceptionHandling();

        $user = User::factory()->create();
        Sanctum::actingAs($user);
        $thread   = Thread::factory()->create([
                                                  'title'      => 'title',
                                                  'content'    => 'content',
                                                  'channel_id' => Channel::factory()->create()->id,
                                                  'user_id'    => $user->id
                                              ]);
        $response = $this->putJson(route('threads.update', $thread), [
            'title'      => 'test',
            'content'    => 'test_content',
            'channel_id' => Channel::factory()->create()->id,
        ])->assertSuccessful();
        $thread->refresh();

        $this->assertSame('test', $thread->title);

    }


    public function test_update_for_best_answer_id_thread()
    {
//        $this->withoutExceptionHandling();

        $user = User::factory()->create();
        Sanctum::actingAs($user);
        $thread   = Thread::factory()->create([
                                                  'user_id' => $user->id
                                              ]);
        $response = $this->putJson(route('threads.update', $thread), [
            'best_answer_id' => 1,
        ])->assertSuccessful();
        $thread->refresh();

        $this->assertSame('1', $thread->answer_id);

    }

    public function test_delete_thread()
    {

        $user = User::factory()->create();
        Sanctum::actingAs($user);
        $thread   = Thread::factory()->create([
                                                  'user_id' => $user->id
                                              ]);
        $response = $this->deleteJson(route('threads.destroy', $thread->id))->assertSuccessful();
    }
}
