<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('stock_withdrawals', function (Blueprint $table) {
            $table->foreignId('requisition_id')
                ->nullable()
                ->after('user_id')
                ->constrained('book_requisitions')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('stock_withdrawals', function (Blueprint $table) {
            $table->dropForeign(['requisition_id']);
            $table->dropColumn('requisition_id');
        });
    }
};
