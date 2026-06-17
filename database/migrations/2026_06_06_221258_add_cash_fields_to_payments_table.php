<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->enum('payment_method', ['momo', 'airtel', 'mock', 'cash'])
                  ->default('mock')
                  ->change();
            $table->boolean('marked_paid_by_admin')->default(false)->after('paid_at');
            $table->foreignId('marked_by_admin_id')->nullable()->constrained('users')->after('marked_paid_by_admin');
        });
    }

    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn(['marked_paid_by_admin', 'marked_by_admin_id']);
        });
    }
};