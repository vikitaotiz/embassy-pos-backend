<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cancel extends Model
{
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function loggers()
    {
        return $this->hasMany(Logger::class);
    }
}
