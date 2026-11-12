
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('vehicle_model', 100)
                ->nullable()
                ->after('mobile');

            $table->string('vehicle_plate', 30)
                ->nullable()
                ->after('vehicle_model');

            $table->string('account_status', 20)
                ->default('pending')
                ->index()
                ->after('role');

            $table->timestamp('account_approved_at')
                ->nullable()
                ->after('account_status');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['account_status']);

            $table->dropColumn([
                'vehicle_model',
                'vehicle_plate',
                'account_status',
                'account_approved_at',
            ]);
        });
    }
};
