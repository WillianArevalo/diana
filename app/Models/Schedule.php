<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    protected $table = "schedule";


    protected $casts = [
        "time_start" => "datetime:H:i",
        "time_end" => "datetime:H:i",
        "break_start" => "datetime:H:i",
        "break_end" => "datetime:H:i",
    ];

    protected $fillable = [
        "type",
        "date_start",
        "date_end",
        "time_start",
        "time_end",
        "break_start",
        "break_end",
        "hours_day",
        "hours_night",
        "workplace_id"
    ];

    public function workplace()
    {
        return $this->belongsTo(Workplace::class);
    }

    public function users()
    {
        return $this->hasMany(User::class, "schedule_id");
    }
}