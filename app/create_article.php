<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class create_article extends Model
{
    protected $table = 'article' ;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
     protected $fillable = [
        'article_id','article_unqid','article_author','author_unqid', 'article_title', 'article_source',
        'article_editor','editor_unqid','article_summary','article_content'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'remember_token','article_unqid','author_unqid','editor_unqid'
    ];
}
