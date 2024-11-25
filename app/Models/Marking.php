<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Marking extends Model
{
    protected $table = "marking";

    protected $casts = [
        "date" => "date",
        "entry_time" => "datetime:H:i",
        "exit_time" => "datetime:H:i",
        "lunch_time_start" => "datetime:H:i",
        "lunch_time_end" => "datetime:H:i",
    ];

    protected $fillable = [
        "date",
        "user_id",
        "entry_time",
        "exit_time",
        "lunch_time_start",
        "lunch_time_end",
        "type",
        "photo",
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
