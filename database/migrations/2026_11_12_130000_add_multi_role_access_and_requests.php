<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('can_be_companion_seeker')
                ->default(false)
                ->after('role');

            $table->boolean('can_be_companion_helper')
                ->default(false)
                ->after('can_be_companion_seeker');
        });

        // Preserve access for accounts registered before multi-role support.
        DB::table('users')
            ->where('role', 'companion_seeker')
            ->update([
                'can_be_companion_seeker' => true,
                'can_be_companion_helper' => false,
            ]);

        DB::table('users')
            ->where('role', 'companion_helper')
            ->update([
                'can_be_companion_seeker' => false,
                'can_be_companion_helper' => true,
            ]);

        Schema::create('companion_role_change_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->boolean('requested_seeker_access');
            $table->boolean('requested_helper_access');
            $table->string('status', 20)->default('pending');
            $table->foreignId('reviewed_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();
            $table->timestamp('reviewed_at')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->timestamps();
            $table->index(['user_id', 'status']);
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('companion_role_change_requests');

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'can_be_companion_seeker',
                'can_be_companion_helper',
            ]);
        });
    }
};
