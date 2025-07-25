<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class AddNotesToQuotationItemsTable extends Migration
{
    public function up()
    {
        if (Schema::hasTable('quotation_items') && !Schema::hasColumn('quotation_items', 'notes')) {
            DB::statement("ALTER TABLE quotation_items ADD COLUMN notes TEXT NULL AFTER tax");
        }
    }

    public function down()
    {
        if (Schema::hasTable('quotation_items') && Schema::hasColumn('quotation_items', 'notes')) {
            Schema::table('quotation_items', function (Blueprint $table) {
                $table->dropColumn('notes');
            });
        }
    }
}
