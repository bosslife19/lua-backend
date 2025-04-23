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
}
