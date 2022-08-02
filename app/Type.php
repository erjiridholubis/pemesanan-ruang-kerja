<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Type extends Model
{
    use SoftDeletes;
    
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'name',
        'price',
        'description'
    ];

    public function room() {
        return $this->belongsTo('App\Room', 'type_id');
    }
}
