<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class FriendshipSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::pluck('id')->toArray(); // Lấy danh sách user_id
        $usedPairs = []; // Lưu các cặp đã sử dụng

        for ($i = 0; $i < 50; $i++) {
            $user1 = $users[array_rand($users)];
            $user2 = $users[array_rand($users)];

            // Không cho phép kết bạn với chính mình
            if ($user1 === $user2) {
                continue;
            }

            // Tạo key để kiểm tra trùng (ví dụ: 2-5 hoặc 5-2 đều là một cặp)
            $pairKey = $user1 < $user2 ? "$user1-$user2" : "$user2-$user1";
            if (in_array($pairKey, $usedPairs)) {
                continue;
            }

            DB::table('friendships')->insert([
                'user_id' => $user1,
                'friend_id' => $user2,
                'status' => collect(['pending', 'accepted', 'blocked'])->random(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $usedPairs[] = $pairKey;
        }
    }
}
