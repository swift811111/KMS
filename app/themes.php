<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class themes extends Model
{
    protected $table = 'themes' ;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
     protected $fillable = [
        'themename', 'foundername', 'public','unqid',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'remember_token','unqid',
    ];
}
