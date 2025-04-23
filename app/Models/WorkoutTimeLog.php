<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkoutTimeLog extends Model
{
    protected $guarded = [];
    protected $casts = [
        'exercises_done' => 'array',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);

       
    }
}
