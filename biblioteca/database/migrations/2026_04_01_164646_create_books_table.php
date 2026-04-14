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
    Schema::create('books', function (Blueprint $table) {
        $table->id();
        $table->foreignId('subject_id')->constrained()->restrictOnDelete();
        $table->string('title');
        $table->string('isbn', 20)->unique();
        $table->string('author', 200)->nullable();
        $table->string('publisher', 150)->nullable();
        $table->string('edition', 20)->nullable();
        $table->unsignedInteger('current_stock')->default(0);
        $table->unsignedInteger('minimum_stock')->default(10);
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};
