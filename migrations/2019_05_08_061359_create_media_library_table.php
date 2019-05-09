<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMediaLibraryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('media_library', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('collection_name')->nullable();
            $table->string('path');
            $table->string('file_name');
            $table->string('alt')->nullable();
            $table->string('mime_type')->nullable();
            $table->unsignedInteger('file_size');
            $table->json('data')->nullable();
            $table->json('image_sizes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('media_library');
    }
}
