<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $fillable = [
        'email', 'name', 'lastname', 'message', 'read', 'apartment_id'
    ];

    public function apartment()
    {
            return $this->belongsTo('App\Apartment');
    }
}
