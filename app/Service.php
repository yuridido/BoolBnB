<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $fillable = [
        'service',
        'description',
        'icon'
    ];

    public function apartments()
    {
            return $this->belongsToMany('App\Apartment');
    }
}
