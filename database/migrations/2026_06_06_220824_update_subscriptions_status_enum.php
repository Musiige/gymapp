<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE subscriptions MODIFY COLUMN status ENUM('active','expired','pending','changed') DEFAULT 'pending'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE subscriptions MODIFY COLUMN status ENUM('active','expired','pending') DEFAULT 'pending'");
    }
};