<?php

namespace Database\Factories;

use App\Models\Channel;
use App\Models\Thread;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ThreadFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Thread::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $title=$this->faker->sentence(3);
        return [
            'title'=>$title,
            'slug'=>Str::slug($title),
            'content'=>$this->faker->realText(),
            'user_id'=>User::factory()->create()->id,
            'channel_id'=>Channel::factory()->create()->id,
        ];
    }
}
