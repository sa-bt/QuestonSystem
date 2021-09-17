<?php

namespace Tests\Unit;

use App\Models\Thread;
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
        $response =$this->get(route('thread.index'));

        $response->assertStatus(Response::HTTP_OK);
    }

    public function test_thread_should_be_accessible_by_id()
    {
        $thread=Thread::factory()->create();
        $response =$this->get(route('thread.show',$thread->id));

        $response->assertStatus(Response::HTTP_OK);
    }
}
