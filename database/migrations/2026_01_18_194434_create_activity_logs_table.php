<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->json('incident'); // store incident data as JSON
            $table->unsignedBigInteger('user_id'); // who made the change
            $table->string('old_status');
            $table->string('new_status');
            $table->text('comment')->nullable();
            $table->timestamps();

            // optional: foreign key constraint
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
