<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class classification extends Model
{
    protected $table = 'classification' ;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
     protected $fillable = [
        'fathername','name', 'foundername', 'id','unqid','foundername_unqid'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'remember_token','unqid','foundername_unqid'
    ];
}
