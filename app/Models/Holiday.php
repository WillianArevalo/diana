<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Holiday extends Model
{
    protected $table = "holidays";

    protected $casts = [
        "date_start" => "date",
        "date_end" => "date"
    ];

    protected $fillable = [
        "name",
        "date_start",
        "date_end",
        "workplace_id"
    ];

    public function workplace()
    {
        return $this->belongsTo(Workplace::class);
    }
}