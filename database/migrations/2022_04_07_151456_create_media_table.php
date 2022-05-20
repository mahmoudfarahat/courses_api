<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('media', function (Blueprint $table) {
            $table->id();
            $table->string('title')->unique();
            $table->string('description');
            $table->string('type');
            $table->string('fileCoverName')->nullable();
            $table->string('fileMediaName')->nullable();
            $table->string('writtenLecture')->nullable();
            $table->string('slug');
            $table->String('link')->nullable();;
            $table->bigInteger('category_id')->unsigned();
            $table->foreign('category_id')->references('id')->on('category')->onDelete('cascade');
            $table->bigInteger('playlist_id')->unsigned()->nullable();
            $table->foreign('playlist_id')->references('id')->on('playlists')->onDelete('cascade');

            $table->Date('Date');
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
        Schema::dropIfExists('media');
    }
};
