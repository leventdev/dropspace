<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * 'code', 
     * 'file_identifier', 
     * 'expiry_date',
     * 'used'
     * 
     * @return void
     */
    public function up()
    {
        Schema::create('share_codes', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('code')->unique();
            $table->string('file_identifier');
            $table->dateTime('expiry_date');
            $table->boolean('used')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('share_codes');
    }
};
