<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Exercise extends Model
{
    protected $guarded = [];

    public function assignedUsers()
{
    return $this->belongsToMany(User::class, 'exercise_user');
}

public function workouts()
{
    return $this->belongsToMany(Workout::class);
}

protected $casts = [
    'videos'=>'array'
];
}
