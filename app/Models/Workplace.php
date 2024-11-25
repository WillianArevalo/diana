<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Workplace extends Model
{
    protected $table = "workplace";
    protected $fillable = ["name", "schedule_id"];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function daytimeSchedule()
    {
        return $this->belongsTo(Schedule::class, 'daytime_schedule_id');
    }

    public function nighttimeSchedule()
    {
        return $this->belongsTo(Schedule::class, 'nighttime_schedule_id');
    }

    public function holidays()
    {
        return $this->hasMany(Holiday::class);
    }
}