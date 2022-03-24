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
        Schema::create('files', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('file_identifier')->unique();
            $table->string('path');
            $table->string('name');
            $table->string('extension');
            $table->string('size');
            $table->string('upload_date');
            $table->string('uploader_ip');
            $table->boolean('is_protected')->default(false);
            $table->string('password', 255)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('files');
    }
};
