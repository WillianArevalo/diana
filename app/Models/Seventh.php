<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Seventh extends Model
{
    protected $table = 'sevent';

    protected $fillable = ['user_id', 'day'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}