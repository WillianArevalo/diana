<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Permission extends Model
{
    use HasFactory;

    protected $table = "permissions";

    protected $casts = [
        "date_start" => "datetime",
        "date_end" => "datetime",
    ];

    protected $fillable = [
        "type",
        "date_start",
        "date_end",
        "description",
        "file",
        "user_id"
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}