<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            
            // Quan hệ chính
            $table->foreignId('conversation_id')->constrained()->onDelete('cascade');
            $table->foreignId('sender_id')->constrained('users')->onDelete('cascade');

            // Nội dung tin nhắn
            $table->enum('message_type', ['text', 'image', 'file', 'video', 'system'])->default('text');
            $table->text('content')->nullable();

            // Trả lời tin nhắn
            $table->unsignedBigInteger('reply_to_id')->nullable();
            $table->foreign('reply_to_id')->references('id')->on('messages')->onDelete('set null');

            // Trạng thái
            $table->enum('status', ['sent', 'delivered', 'read', 'failed', 'deleted'])->default('sent');
            $table->boolean('is_edited')->default(false);
            $table->json('deleted_by')->nullable(); // Nếu nhiều người có thể xoá (ẩn) tin nhắn

            $table->timestamps();
            $table->softDeletes(); // deleted_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};

