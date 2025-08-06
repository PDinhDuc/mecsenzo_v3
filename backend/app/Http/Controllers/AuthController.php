<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Events\UserOnlineStatusUpdated;
use Illuminate\Support\Facades\Cache;

class AuthController extends Controller
{
    public function login(Request $request){
        $user = User::where('email', $request->email)->first();
        if (! $user || ! Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }
        $user->update(['is_online' => true]);
        broadcast(new UserOnlineStatusUpdated($user));
        return response()->json([
            'token' => $user->createToken('api-token')->plainTextToken,
        ]);
    }

    public function register(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
        $token = $user->createToken('api-token')->plainTextToken;
        return response()->json([
            'token' => $token,
            'user' => $user,
        ]);
    }

    public function logout(Request $request){
        $user = Auth::user();
        $user->update(['is_online' => false]);
        broadcast(new UserOnlineStatusUpdated($user));
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Logged out']);
    }

    public function searchUser(Request $request)
    {
        $query = $request->input('query');

        if (!$query) {
            return response()->json([], 200);
        }

        $authId = Auth::id();

        $users = User::where(function ($q) use ($query) {
                $q->where('email', 'like', "%$query%")
                ->orWhere('name', 'like', "%$query%");
            })
            ->where('users.id', '!=', $authId)
            ->leftJoin('friendships', function ($join) use ($authId) {
                $join->on(function ($q) use ($authId) {
                    $q->on('users.id', '=', 'friendships.user_id')
                    ->where('friendships.friend_id', '=', $authId);
                })->orOn(function ($q) use ($authId) {
                    $q->on('users.id', '=', 'friendships.friend_id')
                    ->where('friendships.user_id', '=', $authId);
                });
            })
            ->select('users.id', 'users.name', 'users.email', 'users.avatar', 'friendships.status', 'friendships.user_id', 'friendships.id as friendship_id')
            ->paginate(10);

        return response()->json($users);
    }

    public function getUser(Request $request){
        return $request->user();
    }
}
