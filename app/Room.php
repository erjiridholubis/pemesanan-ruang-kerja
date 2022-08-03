<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Room extends Model
{
    use SoftDeletes;
    
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'type_id',
        'name',
        'image',
    ];
    
    public function type() {
        return $this->hasMany('App\Type', 'type_id');
    }

    public function facility() {
        return $this->belongsToMany('App\Facility', 'facility_room', 'room_id', 'facility_id');
    }

    public function order() {
        return $this->hasMany('App\Order', 'room_id', 'id');
    }
}
