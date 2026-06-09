<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('book_subject', function (Blueprint $table) {
            $table->foreignId('book_id')->constrained()->cascadeOnDelete();
            $table->foreignId('subject_id')->constrained()->cascadeOnDelete();
            $table->primary(['book_id', 'subject_id']);
        });

        // Migrar dados existentes de subject_id para a tabela pivot
        DB::table('books')
            ->whereNotNull('subject_id')
            ->get()
            ->each(function ($book) {
                DB::table('book_subject')->insert([
                    'book_id'    => $book->id,
                    'subject_id' => $book->subject_id,
                ]);
            });

        Schema::table('books', function (Blueprint $table) {
            $table->dropForeign(['subject_id']);
            $table->dropColumn('subject_id');
        });
    }

    public function down(): void
    {
        Schema::table('books', function (Blueprint $table) {
            $table->foreignId('subject_id')->nullable()->constrained()->nullOnDelete();
        });

        // Restaurar subject_id com o primeiro vínculo de cada livro
        DB::table('book_subject')->get()->each(function ($pivot) {
            DB::table('books')
                ->where('id', $pivot->book_id)
                ->whereNull('subject_id')
                ->update(['subject_id' => $pivot->subject_id]);
        });

        Schema::dropIfExists('book_subject');
    }
};
