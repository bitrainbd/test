<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mcq extends Model
{
    protected $guarded = [];

    public function mcq_answers()
    {
        return $this->hasMany(McqAnswer::class);
    }

    public function mcq_attempts()
    {
        return $this->hasMany(McqAttempt::class);
    }
    
    public function mcq_stats()
    {
        return $this->hasMany(McqStat::class);
    }
}
