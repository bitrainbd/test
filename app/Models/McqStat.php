<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class McqStat extends Model
{
    public function mcq()
    {
        return $this->belongsTo(Mcq::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
