<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE book_requisitions MODIFY COLUMN status ENUM('pending','approved','dispatched','delivered','cancelled') NOT NULL DEFAULT 'pending'");

        Schema::table('book_requisitions', function (Blueprint $table) {
            $table->date('estimated_delivery_from')->nullable()->after('approved_at');
            $table->date('estimated_delivery_to')->nullable()->after('estimated_delivery_from');
            $table->timestamp('dispatched_at')->nullable()->after('estimated_delivery_to');
            $table->string('delivered_by', 150)->nullable()->after('dispatched_at');
        });
    }

    public function down(): void
    {
        Schema::table('book_requisitions', function (Blueprint $table) {
            $table->dropColumn(['estimated_delivery_from', 'estimated_delivery_to', 'dispatched_at', 'delivered_by']);
        });

        DB::statement("ALTER TABLE book_requisitions MODIFY COLUMN status ENUM('pending','approved','delivered','cancelled') NOT NULL DEFAULT 'pending'");
    }
};
