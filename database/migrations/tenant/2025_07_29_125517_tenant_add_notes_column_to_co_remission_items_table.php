<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TenantAddNotesColumnToCoRemissionItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('co_remission_items', function (Blueprint $table) {
            $table->text('notes')->nullable()->after('tax'); // para que aparezca antes de la columna
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table('co_remission_items', function (Blueprint $table) {
            $table->dropColumn('notes');
        });
    }
}

