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
        Schema::table('vendors', function (Blueprint $table) {
            // Adds an unsigned BIGINT equivalent column and foreign key constraint
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            // You can specify where to place the column, e.g., after the 'name' column:
            // $table->foreignId('user_id')->constrained()->cascadeOnDelete()->after('name'); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vendors', function (Blueprint $table) {
            $table->dropConstrainedForeignId('user_id')->after('id'); // Drops the foreign key and the column
        });
    }
};
