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
        Schema::create('hiddenimages', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->user_id_hidding();
            $table->user_id_hidden_from();
            $table->image_id();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('hiddenimages');
    }
};
