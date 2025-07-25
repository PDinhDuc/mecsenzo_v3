<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use app\models\user;
use app\models\conversation;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class ConversationUserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $userIds = User::pluck('id')->toArray();
        $conversationIds = Conversation::pluck('id')->toArray();

        $userId = $this->faker->randomElement($userIds);
        $conversationId = $this->faker->randomElement($conversationIds);

        return [
            'user_id' => $userId,
            'conversation_id' => $conversationId,
        ];
    }
}
