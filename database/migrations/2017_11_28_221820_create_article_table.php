<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateArticleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('article', function (Blueprint $table) {
            $table->increments('article_id');
            $table->string('article_unqid');
            $table->string('article_author');
            $table->string('author_unqid');
            $table->string('article_editor')->nullable();
            $table->string('editor_unqid')->nullable();
            $table->string('article_title');
            $table->string('article_source')->nullable();
            $table->string('article_summary')->nullable();
            $table->text('article_content');
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
        Schema::dropIfExists('article');
    }
}
