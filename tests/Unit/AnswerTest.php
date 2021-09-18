<?php

namespace Tests\Unit;

use App\Models\Answer;
use App\Models\Thread;
use App\Models\User;
use Laravel\Sanctum\Sanctum;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class AnswerTest extends TestCase
{


    public function test_get_all_answers()
    {
        $response = $this->get(route('answers.index'));
        $response->assertStatus(Response::HTTP_OK);
    }


    public function test_create_answer()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);
        $this->withoutExceptionHandling();
        $thread   = Thread::factory()->create();
        $response = $this->postJson(route('answers.store'), [
            'content'   => 'test',
            'thread_id' => $thread->id,
        ]);

        $response->assertStatus(Response::HTTP_CREATED);
        $this->assertTrue($thread->answers()->whereContent('test')->exists());
        $response->assertJson([
                                  'message' => 'answer created successfully.'
                              ]);
    }


    public function test_answer_before_create_should_be_validate()
    {
        $response = $this->postJson(route('answers.store'), []);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors(['content', 'thread_id']);

    }

    public function test_update_answer_validate()
    {
        $answer   = Answer::factory()->create();
        $response = $this->putJson(route('answers.update', $answer), []);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors(['content']);
    }

    public function test_update_answer()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);
        $thread   = Thread::factory()->create();
        $answer   = Answer::factory()->create([
                                                  'user_id'   => $user->id,
                                                  'thread_id' => $thread->id
                                              ]);
        $response = $this->putJson(route('answers.update', $answer->id), [
            'content' => 'test_update_content',
        ])->assertSuccessful();
        $answer->refresh();

        $this->assertSame('test_update_content', $answer->content);
        $this->assertEquals('test_update_content', $answer->content);
        $this->assertTrue($thread->answers()->whereContent('test_update_content')->exists());
        $response->assertJson([
                                  'message' => 'answer updated successfully.'
                              ]);
    }


    public function test_delete_answer()
    {
        $user=Sanctum::actingAs(User::factory()->create());
        $answer   = Answer::factory()->create([
            'user_id'=>$user->id
                                              ]);
        $response = $this->deleteJson(route('answers.destroy', $answer->id))->assertSuccessful();
        $this->assertNull(Answer::query()->find($answer->id));
        $response->assertJson([
                                  'message' => 'answer deleted successfully.'
                              ]);    }
}
