<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\User;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\FriendRequestController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ConversationController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Broadcast;
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

Broadcast::routes(['middleware' => ['auth:sanctum']]);

Route::group(['prefix' => 'auth'],function (){
    Route::post('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/register',[AuthController::class, 'register']);
    Route::middleware('auth:sanctum')->group(function (){
        Route::get('/logout', [AuthController::class, 'logout']);
        Route::get('/user', [AuthController::class, 'getUser']);
    });
});

Route::middleware('auth:sanctum')->get('/search-friends', [AuthController::class, 'searchUser']);
//route friend
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/friend-request/{id}/invitation', [FriendRequestController::class, 'sendRequest']);
    Route::post('/friend-request/{id}/accept', [FriendRequestController::class, 'acceptRequest']);
    Route::post('/friend-request/{id}/decline', [FriendRequestController::class, 'declineRequest']);
    Route::delete('/friend-request/{id}/cancel', [FriendRequestController::class, 'cancelRequest']);
    Route::get('/friend-request/received', [FriendRequestController::class, 'receivedRequests']);
    Route::post('/friend', [FriendRequestController::class, 'getFriendOfUser']);
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
    Route::get('/conversations/{id}', [ConversationController::class, 'getInforConversation']);
    Route::get('/conversations-individual', [ConversationController::class, 'getConversationIndividual']);
    Route::get('/conversations-space', [ConversationController::class, 'getConversationSpace']);
    Route::get('/conversations/with/{userId}', [ConversationController::class, 'getConversationWithUser']);
    Route::get('/conversations/{id}/messages', [ConversationController::class, 'loadMessages']);
    Route::post('conversations/{id}/send', [ConversationController::class, 'sendMessage']);
});