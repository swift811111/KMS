<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateThemeClsGroupTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('theme_cls_group', function (Blueprint $table) {
            $table->increments('id');
            $table->string('username');
            $table->string('user_unqid');
            $table->string('theme_group_name');
            $table->string('theme_group_unqid');
            $table->string('theme_name');
            $table->string('theme_unqid');
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
        Schema::dropIfExists('theme_cls_group');
    }
}
