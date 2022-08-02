<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    protected $table = "statuses";
    protected $fillable = ['status'];

    public function post() {
      return $this->hasMany('App\Post','user_id','id');
    }
    public function page() {
      return $this->hasMany('App\Page','user_id','id');
    }
}
