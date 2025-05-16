<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // First, copy any photo values to image if image is null
        DB::table('products')
            ->whereNull('image')
            ->whereNotNull('photo')
            ->update(['image' => DB::raw('photo')]);

        // Then remove the photo column
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('photo');
        });
    }

    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('photo')->nullable();
        });
    }
}; 