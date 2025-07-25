<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\User;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\FriendRequestController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ConversationController;
use App\Http\Controllers\AuthController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
//route auth
Route::group(['prefix' => 'auth'],function (){
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register',[AuthController::class, 'register']);
    Route::middleware('auth:sanctum')->group(function (){
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/user', [AuthController::class, 'getUser']);
    });
});

Route::middleware('auth:sanctum')->get('/search-friends', [AuthController::class, 'searchUser']);
//route friend
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/friend-request', [FriendRequestController::class, 'sendRequest']);
    Route::post('/friend-request/{id}/accept', [FriendRequestController::class, 'acceptRequest']);
    Route::post('/friend-request/{id}/decline', [FriendRequestController::class, 'declineRequest']);
    Route::get('/friend-request/received', [FriendRequestController::class, 'receivedRequests']);
});
//route notification
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/notifications', [NotificationController::class, 'index']);
    Route::get('/notifications/unread', [NotificationController::class, 'unread']);
    Route::post('/notifications/read/{id}', [NotificationController::class, 'markAsRead']);
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead']);
    Route::delete('/notifications/{id}', [NotificationController::class, 'destroy']);
    Route::delete('/notifications', [NotificationController::class, 'clearAll']);
});
//route conversation / messages
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/conversations', [ConversationController::class, 'getConversationOfUser']);
    Route::get('/conversations/with/{userId}', [ConversationController::class, 'getConversationWithUser']);
    Route::get('/conversations/{id}/messages', [ConversationController::class, 'loadMessages']);
    Route::post('conversations/{id}/send', [ConversationController::class, 'sendMessage']);
});