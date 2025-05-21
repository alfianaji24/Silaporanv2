<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('wa_message_statuses', function (Blueprint $table) {
            $table->id();
            $table->string('nik', 16); // Employee NIK
            $table->string('phone_number', 20); // Recipient's phone number
            $table->string('message_id')->nullable(); // WhatsApp message ID
            $table->enum('status', ['pending', 'sent', 'delivered', 'read', 'failed'])->default('pending');
            $table->text('message_content')->nullable(); // The actual message content
            $table->text('error_message')->nullable(); // Error message if status is failed
            $table->timestamp('sent_at')->nullable(); // When the message was sent
            $table->timestamp('delivered_at')->nullable(); // When the message was delivered
            $table->timestamp('read_at')->nullable(); // When the message was read
            $table->timestamps();

            // Add indexes for better query performance
            $table->index('nik');
            $table->index('phone_number');
            $table->index('status');
            $table->index('message_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wa_message_statuses');
    }
};
