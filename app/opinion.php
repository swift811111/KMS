<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class opinion extends Model
{
    protected $table = 'opinion' ;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
     protected $fillable = [
        'id','username', 'user_unqid','article_unqid','opinion_content','best','bad'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'remember_token', 'user_unqid','article_unqid'
    ];
}
