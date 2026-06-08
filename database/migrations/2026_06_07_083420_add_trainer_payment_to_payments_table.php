<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->foreignId('marked_by_trainer_id')
                  ->nullable()
                  ->constrained('users')
                  ->after('marked_by_admin_id');
        });
    }

    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropForeign(['marked_by_trainer_id']);
            $table->dropColumn('marked_by_trainer_id');
        });
    }
};