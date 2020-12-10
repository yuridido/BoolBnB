<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sponsor extends Model
{
    protected $fillable = [
        'sponsor', 'sponsor_price', 'sponsor_time'
    ];

    public function apartments()
    {
            return $this->belongsToMany('App\Apartment')
            ->withPivot('end_sponsor')
            ->withTimestamps();
    }
}
