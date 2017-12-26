<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent;

class theme_group extends Model
{
    protected $table = 'theme_group' ;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
     protected $fillable = [
        'username', 'user_unqid', 'theme_group_name','theme_group_unqid',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'remember_token','theme_group_unqid','user_unqid'
    ];

    
}
