
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
            $table->string('email')->nullable()->change();

            $table->string('national_code', 10)
                ->nullable()
                ->unique();

            $table->string('mobile', 15)
                ->nullable()
                ->unique();

            $table->string('role', 30)
                ->default('companion_seeker')
                ->index();

            $table->timestamp('mobile_verified_at')
                ->nullable();
        });
    }

    public function down(): void
    {
        if (DB::table('users')->whereNull('email')->exists()) {
            throw new \RuntimeException(
                'Migration rollback stopped: users with null email exist. '
                . 'Restore their email addresses before rolling back.'
            );
        }

        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique(['national_code']);
            $table->dropUnique(['mobile']);
            $table->dropIndex(['role']);

            $table->dropColumn([
                'national_code',
                'mobile',
                'role',
                'mobile_verified_at',
            ]);

            $table->string('email')->nullable(false)->change();
        });
    }
};
