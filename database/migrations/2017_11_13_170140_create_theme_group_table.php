<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateThemeGroupTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('theme_group', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('foundername');
            $table->string('foundername_unqid');
            $table->string('theme_name')->nullable();
            $table->json('theme_name_json')->nullable();
            $table->string('unqid',50)->unique();
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
        Schema::dropIfExists('theme_group');
    }
}
