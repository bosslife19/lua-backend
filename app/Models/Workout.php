<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Workout extends Model
{
    protected $guarded = [];

    public function exercises()
{
    return $this->belongsToMany(Exercise::class);
}
}
