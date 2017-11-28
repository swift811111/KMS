<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class search_association extends Model
{
    protected $table = 'search_association' ;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
     protected $fillable = [
        'id','user', 'user_unqid', 'theme_unqid',
        'cls_f_unqid','cls_c_unqid','article_unqid'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'remember_token', 'user_unqid'
    ];
}
