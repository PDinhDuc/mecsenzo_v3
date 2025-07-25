<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use app\models\conversation;
use app\models\user;
use App\Models\Messages;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Messages>
 */
class MessagesFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Messages::class;

    public function definition(): array
    {
        // Lấy random 1 conversation
        $conversation = Conversation::inRandomOrder()->first();

        // Lấy danh sách user_id tham gia cuộc trò chuyện này
        $userIds = $conversation->users()->pluck('users.id')->toArray();

        return [
            'conversation_id' => $conversation->id,
            'user_id' => fake()->randomElement($userIds),
            'content' => fake()->sentence(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
