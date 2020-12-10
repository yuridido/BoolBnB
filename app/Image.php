<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    protected $fillable = [
        'path', 'apartment_id'
    ];

    public function apartment()
    {
      return $this->belongsTo('App\Apartment');
    }
}
