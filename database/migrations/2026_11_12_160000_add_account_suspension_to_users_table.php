<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            $table->timestamp('blocked_at')->nullable()->after('account_status');
            $table->text('blocked_reason')->nullable()->after('blocked_at');
            $table->foreignId('blocked_by')
                ->nullable()
                ->after('blocked_reason')
                ->constrained('users')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            $table->dropConstrainedForeignId('blocked_by');
            $table->dropColumn(['blocked_at', 'blocked_reason']);
        });
    }
};
