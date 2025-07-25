<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use app\models\user;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Friendships>
 */
class FriendshipsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $userIds = User::pluck('id')->toArray();
        $userId = fake()->randomElement($userIds);
    
        // Lấy friendId khác với userId
        $friendIds = array_filter($userIds, fn($id) => $id !== $userId);
        $friendId = fake()->randomElement($friendIds);

        return [
            'user_id' => $userId,
            'friend_id' => $friendId,
            'status' => fake()->randomElement(['pending', 'accepted', 'blocked']), // hoặc random status nếu bạn dùng enum
        ];
    }
}
