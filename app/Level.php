<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Level extends Model
{
    protected $table = "levels";
    protected $fillable = ['level'];

    public function user() {
      return $this->hasMany('App\User','level_id','id');
    }
}
