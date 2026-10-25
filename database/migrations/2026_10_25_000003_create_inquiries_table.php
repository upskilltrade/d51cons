<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inquiries', function (Blueprint $table) {
            $table->id();
            $table->string('name', 120);
            $table->string('email', 190);
            $table->string('phone', 40)->nullable();
            $table->string('service_type', 100)->nullable();
            $table->string('location', 190)->nullable();
            $table->string('budget_range', 30)->nullable();
            $table->text('message');
            $table->string('locale', 5);
            $table->timestamp('privacy_acknowledged_at');
            $table->string('status', 20)->default('new');
            $table->string('mail_status', 20)->default('pending');
            $table->string('provider_message_id')->nullable();
            $table->timestamp('notified_at')->nullable();
            $table->timestamps();

            $table->index(['status', 'created_at']);
            $table->index('mail_status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inquiries');
    }
};
