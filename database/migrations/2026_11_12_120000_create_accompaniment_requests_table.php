<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('accompaniment_requests', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->string('origin_city', 100);
            $table->string('origin_location', 255);
            $table->string('destination_city', 100);
            $table->string('destination_location', 255);
            $table->string('type', 20);
            $table->dateTime('departure_at');
            $table->dateTime('return_at')->nullable();

            // Passenger count includes the companion seeker.
            $table->unsignedTinyInteger('passenger_count');

            $table->text('description')->nullable();
            $table->string('status', 20)->default('open')->index();
            $table->timestamps();

            $table->index(['departure_at', 'status']);
            $table->index(['origin_city', 'destination_city']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('accompaniment_requests');
    }
};
