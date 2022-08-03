<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{

    use SoftDeletes;
        
    protected $fillable = [
        'customer_id', 'room_id', 'start_date', 'end_date', 'booking_date'
    ];

    protected $dates = ['deleted_at'];
    
    public function customer() {
        return $this->belongsTo('App\Customer', 'customer_id');
    }

    public function room() {
        return $this->belongsTo('App\Room', 'room_id');
    }

    public function payment() {
        return $this->hasMany('App\Payment', 'order_id', 'id');
    }
}
 