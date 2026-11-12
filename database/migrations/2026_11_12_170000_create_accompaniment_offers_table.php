<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('accompaniment_offers', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('accompaniment_request_id')->constrained()->cascadeOnDelete();
            $table->foreignId('helper_id')->constrained('users')->cascadeOnDelete();
            $table->unsignedTinyInteger('reserved_passenger_count');
            $table->string('status', 20)->default('pending');
            $table->timestamp('responded_at')->nullable();
            $table->timestamps();

            $table->unique(['accompaniment_request_id', 'helper_id']);
            $table->index(['helper_id', 'status']);
            $table->index(['accompaniment_request_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('accompaniment_offers');
    }
};
