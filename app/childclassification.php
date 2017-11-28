<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class childclassification extends Model
{
    protected $table = 'childclassifications' ;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
     protected $fillable = [
        'clssificationfathername','name', 'foundername', 'id','unqid','foundername_unqid'
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
