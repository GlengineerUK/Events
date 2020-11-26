<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = [
        'name',
        'location',
        'date'
    ];

    public function shifts()
    {
        return $this->hasMany(Shift::class);
    }
}
