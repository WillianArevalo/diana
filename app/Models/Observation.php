<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Observation extends Model
{
    protected $fillable = [
        "type",
        "start_date",
        "end_date",
        "description",
        "file",
        "user_id"
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
