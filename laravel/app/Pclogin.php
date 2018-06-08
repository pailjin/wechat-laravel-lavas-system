<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pclogin extends Model
{
    //
    protected $table = 'pclogin';

    protected $fillable = [
      'scene', 'openid','status' ,'userid'
    ];

}
