<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WorkerType extends Model
{
    protected $fillable = [
        'name',
        'description',
    ];

    protected $appends = ['quantity'];
    public function shifts()
    {
        return $this->belongsToMany(Shift::class)->withPivot('quantity');
    }

    public function getQuantityAttribute()
    {
        $shift = $this->belongsToMany(Shift::class)->withPivot('quantity');
        return $shift->first()->pivot->quantity ?? 0;
    }
}
