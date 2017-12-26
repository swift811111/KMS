<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOpinionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('opinion', function (Blueprint $table) {
            $table->increments('id');
            $table->string('username');
            $table->string('user_unqid');
            $table->string('article_unqid');
            $table->text('opinion_content');
            $table->integer('best');
            $table->integer('bad');
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
        Schema::dropIfExists('opinion');
    }
}
