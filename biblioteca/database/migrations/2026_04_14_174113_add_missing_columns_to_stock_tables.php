<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('stock_entries', function (Blueprint $table) {
            $table->foreignId('book_id')->constrained('books');
            $table->foreignId('user_id')->constrained('users');
            $table->integer('quantity');
            $table->integer('stock_before');
            $table->integer('stock_after');
            $table->text('notes')->nullable();
            $table->datetime('received_at');
        });

        Schema::table('stock_withdrawals', function (Blueprint $table) {
            $table->foreignId('book_id')->constrained('books');
            $table->foreignId('user_id')->constrained('users');
            $table->integer('quantity');
            $table->integer('stock_before');
            $table->integer('stock_after');
            $table->string('class_group', 100);
            $table->text('reason');
            $table->datetime('withdrawn_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stock_entries', function (Blueprint $table) {
            $table->dropForeign(['book_id']);
            $table->dropForeign(['user_id']);
            $table->dropColumn(['book_id', 'user_id', 'quantity', 'stock_before', 'stock_after', 'notes', 'received_at']);
        });

        Schema::table('stock_withdrawals', function (Blueprint $table) {
            $table->dropForeign(['book_id']);
            $table->dropForeign(['user_id']);
            $table->dropColumn(['book_id', 'user_id', 'quantity', 'stock_before', 'stock_after', 'class_group', 'reason', 'withdrawn_at']);
        });
    }
};
