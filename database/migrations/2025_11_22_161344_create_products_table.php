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
        Schema::table('products', function (Blueprint $table) {
            // Add the foreign key column, making it nullable as required
            $table->foreignId('category_id')
                  ->nullable()
                  ->after('stock')
                  ->constrained() // This assumes the table is 'categories' and column is 'id'
                  ->onDelete('set null'); // Required feature

            // Add an index for faster lookups
            $table->index('category_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
            $table->dropColumn('category_id');
        });
    }
};
