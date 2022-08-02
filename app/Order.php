<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{

    use SoftDeletes;
    
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'customer_id', 'room_id', 'start_date', 'end_date', 'booking_date'
    ];

    public function customer() {
        return $this->hasMany('App\Customer', 'customer_id');
    }

    public function room() {
        return $this->hasMany('App\Room', 'room_id');
    }

    public function payment() {
        return $this->belongsTo('App\Payment', 'order_id');
    }
}
 