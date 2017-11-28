<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChildClassificationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('childclassifications', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('foundername');
            $table->string('foundername_unqid');
            $table->string('clssificationfathername');
            $table->string('unqid');
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
        Schema::dropIfExists('childclassifications');
    }
}
