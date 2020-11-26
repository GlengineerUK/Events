<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Event;
use App\WorkerType;


class Shift extends Model
{
    protected $fillable = [
        'name',
        'start_time',
        'end_time',
    ];
    public function events()
    {
        return $this->belongsTo(Event::class);
    }
    public function workerTypes()
    {
        return $this->BelongsToMany(WorkerType::class)->withPivot('quantity');
    }
}
