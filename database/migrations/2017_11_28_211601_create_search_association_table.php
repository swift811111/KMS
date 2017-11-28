<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSearchAssociationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('search_association', function (Blueprint $table) {
            $table->increments('id');
            $table->string('user');
            $table->string('user_unqid');
            $table->string('article_unqid');
            $table->string('theme_unqid');
            $table->string('cls_f_unqid');
            $table->string('cls_c_unqid');
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
        Schema::dropIfExists('search_association');
    }
}
