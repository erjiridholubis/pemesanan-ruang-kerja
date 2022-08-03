<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Facility extends Model
{
    use SoftDeletes;
    
    
    protected $fillable = [
        'name'
    ];
    
    protected $dates = ['deleted_at'];
    
    public function room() {
        return $this->belongsToMany('App\Room');
    }
}
