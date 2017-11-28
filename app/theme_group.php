<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class theme_group extends Model
{
    protected $table = 'theme_group' ;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
     protected $fillable = [
        'name', 'foundername', 'theme_name','theme_name_json','unqid','foundername_unqid'
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
