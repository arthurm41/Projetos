<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    
    public function up(): void {
    Schema::create('stock_withdrawals', function (Blueprint $table) {
        $table->id();
        $table->foreignId('book_id')->constrained()->restrictOnDelete();
        $table->foreignId('user_id')->constrained()->restrictOnDelete();
        $table->unsignedInteger('quantity');
        $table->unsignedInteger('stock_before');
        $table->unsignedInteger('stock_after');
        $table->string('class_group', 100)->nullable();
        $table->text('reason')->nullable();
        $table->timestamp('withdrawn_at')->useCurrent();
        $table->timestamps();
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_withdrawals');
    }
};
