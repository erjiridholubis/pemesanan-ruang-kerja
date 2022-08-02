<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model

{
    use SoftDeletes;
    
    
    protected $fillable = [
        'name', 'phone', 'email', 'password',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];
    
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    
    protected $dates = ['deleted_at'];

    public function order() {
        return $this->hasMany('App\Order', 'customer_id');
    }
    
}
