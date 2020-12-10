<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Apartment extends Model
{

    protected $fillable = [
        'title', 'rooms', 'beds', 'bathrooms', 'sm', 'address', 'latitude', 'longitude', 'city', 'postal_code', 'country', 'daily_price', 'description', 'user_id'
    ];

    public function user()
    {
            return $this->belongsTo('App\User');
    }

    public function images()
    {
            return $this->hasMany('App\Image');
    }

    public function messages()
    {
            return $this->hasMany('App\Message');
    }

    public function services()
    {
            return $this->belongsToMany('App\Service');
    }

    public function sponsors()
    {
            return $this->belongsToMany('App\Sponsor')
            ->withPivot('end_sponsor')
            ->withTimestamps();
    }

    public function views()
    {
        return $this->hasMany('App\View');
    }
}
