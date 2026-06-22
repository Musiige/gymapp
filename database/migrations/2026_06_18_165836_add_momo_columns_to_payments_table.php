<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            // MTN's own UUID for this Request-to-Pay transaction.
            // Used to check status and to match incoming webhook callbacks.
            $table->uuid('momo_reference_id')->nullable()->index()->after('transaction_id');

            // MTN's lifecycle state for THIS transaction attempt, separate
            // from your existing `status` (paid/half-paid/unpaid), which
            // tracks the subscription's overall payment state across
            // possibly multiple attempts.
            $table->enum('momo_status', ['pending', 'successful', 'failed'])
                ->nullable()
                ->after('momo_reference_id');

            // The amount actually requested via THIS MoMo attempt — needed
            // because `amount_paid` on the row may represent a running
            // total across partial payments, while this is just one attempt.
            $table->decimal('momo_amount_requested', 12, 2)->nullable()->after('momo_status');
        });
    }

    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn(['momo_reference_id', 'momo_status', 'momo_amount_requested']);
        });
    }
};