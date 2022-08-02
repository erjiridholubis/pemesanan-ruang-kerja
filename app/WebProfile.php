<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WebProfile extends Model
{
    protected $table = "web_profile";
    protected $fillable = ['logo','thumbnail','title','slogan','description','version','phone','email','ig','line','created_at','updated_at'];

}
