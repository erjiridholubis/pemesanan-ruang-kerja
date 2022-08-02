<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Facility extends Model
{
    use SoftDeletes;
    
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'name'
    ];
    
    public function room() {
        return $this->belongsToMany('App\Room', 'facility_room', 'facility_id', 'room_id');
    }
}
