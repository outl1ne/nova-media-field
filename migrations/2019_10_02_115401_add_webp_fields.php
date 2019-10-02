<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddWebpFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('media_library', function (Blueprint $table) {
            $table->string('webp_name')->nullable();
            $table->unsignedInteger('webp_size')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('media_library', function (Blueprint $table) {
            $table->dropColumn('webp_name');
            $table->dropColumn('webp_size');
        });
    }
}
