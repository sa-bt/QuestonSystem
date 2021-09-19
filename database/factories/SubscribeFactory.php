<?php

namespace Database\Factories;

use App\Models\Subscribe;
use App\Models\Thread;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class SubscribeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Subscribe::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'thread_id'=>Thread::factory()->create()->id,
            'user_id'=>User::factory()->create()->id
        ];
    }
}
