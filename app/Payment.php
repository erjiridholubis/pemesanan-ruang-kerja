<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends Model
{
    use SoftDeletes;
    
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'order_id', 'user_id', 'payment_date', 'proof', 'status', 'payment_deadline'
    ];
    
    public function order() {
        return $this->HasMany('App\Order', 'order_id');
    }

    public function user() {
        return $this->HasMany('App\User', 'user_id');
    }
}
